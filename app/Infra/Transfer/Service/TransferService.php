<?php

namespace App\Infra\Transfer\Service;

use App\Domain\Transfer\Port\Out\TransferRepositoryInterface;
use App\Domain\Transfer\InputOutputData\TransferInput;
use App\Domain\Transfer\Port\Out\AuthorizerServiceInterface;
use App\Domain\Transfer\Port\Out\NotificationServiceInterface;
use App\Model\Transfer;

class TransferService
{
    public function __construct(
        private TransferRepositoryInterface $repository,
        private AuthorizerServiceInterface $authorizer,
        private NotificationServiceInterface $notifier
    ) {
    }

    public function execute(TransferInput $input): array
    {
        $this->repository->beginTransaction();
        try {
            $jwtUserId = $this->getAuthenticatedUserId();
            if ($jwtUserId === null || $jwtUserId !== $input->payer) {
                $this->repository->rollback();
                return $this->buildResponse('error', 403, 'Usuário autenticado não corresponde ao pagador da transferência.', false, false, $input);
            }
            if (! $this->isAuthorized()) {
                $this->repository->rollback();
                return $this->buildResponse('reversed', 403, 'Transferência extornada: não autorizada pelo serviço externo.', false, false, $input);
            }
            $this->validateSufficientBalance($input);
            $this->debitPayerWallet($input);
            $this->creditPayeeWallet($input);
            $transferModel = $this->createPendingTransfer($input);
            $notified = $this->notifier && $this->notifier->notify([
                'payee' => $input->payee,
                'payee_name' => \App\Model\User::find($input->payee)?->full_name,
                'value' => $input->value,
            ]);
            $this->markTransferSuccess($transferModel, $input);
            $this->repository->commit();
            $notifyMsg = $notified ? '' : ' (Atenção: não foi possível notificar o recebedor)';
            return $this->buildResponse('success', 200, $transferModel->message . $notifyMsg, true, $notified, $input);
        } catch (\Throwable $e) {
            $this->repository->rollback();
            $msg = $this->formatErrorMessage($e->getMessage());
            if (isset($transferModel)) {
                $this->markTransferFailed($transferModel, $msg);
            }
            return $this->buildResponse('error', 500, $msg, false, false, $input);
        }
    }

    private function getAuthenticatedUserId(): ?int
    {
        $request = \Hyperf\Context\Context::get(\Psr\Http\Message\ServerRequestInterface::class);
        $authHeader = $request?->getHeaderLine('Authorization');
        if (! $authHeader || ! str_starts_with($authHeader, 'Bearer ')) {
            return null;
        }
        $token = substr($authHeader, 7);
        try {
            $decoded = \Firebase\JWT\JWT::decode($token, new \Firebase\JWT\Key(getenv('JWT_SECRET') ?: 'secret', 'HS256'));
            return isset($decoded->sub) ? (int) $decoded->sub : null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function validateSufficientBalance(TransferInput $input): void
    {
        $payer = \App\Model\User::find($input->payer);
        if (! $payer || ! $payer->wallet_id) {
            throw new \DomainException('Carteira do pagador não encontrada.');
        }
        $payerWallet = \App\Model\Wallet::find($payer->wallet_id);
        if (! $payerWallet) {
            throw new \DomainException('Carteira do pagador não encontrada.');
        }
        $payerBalance = (float) $payerWallet->balance;
        $value = (float) $input->value;
        if ($payerBalance < $value) {
            $this->repository->rollback();
            throw new \DomainException('Saldo insuficiente para realizar a transferência.');
        }
    }

    private function createPendingTransfer(TransferInput $input): Transfer
    {
        return Transfer::create([
            'payer_id' => $input->payer,
            'payee_id' => $input->payee,
            'value' => $input->value,
            'status' => 'pending',
            'message' => null,
        ]);
    }

    private function isAuthorized(): bool
    {
        return $this->authorizer && $this->authorizer->authorize();
    }

    private function markTransferSuccess(Transfer $transferModel, TransferInput $input): void
    {
        $payeeUser = \App\Model\User::find($input->payee);
        $payeeName = $payeeUser ? $payeeUser->full_name : $input->payee;
        $transferModel->status = 'success';
        $transferModel->message = "Transferência PIX de R$ {$input->value} para o usuário {$payeeName} realizada com sucesso.";
        $transferModel->save();
    }

    private function reverseTransfer(TransferInput $input, Transfer $transferModel): void
    {
        $this->repository->rollback();
        $this->repository->beginTransaction();
        $this->repository->transfer(new TransferInput([
            'payer' => $input->payee,
            'payee' => $input->payer,
            'value' => $input->value,
        ]));
        $transferModel->status = 'reversed';
        $transferModel->message = 'Transferência extornada: não autorizada pelo serviço externo.';
        $transferModel->save();
        $this->repository->commit();
    }

    private function markTransferFailed(Transfer $transferModel, string $msg): void
    {
        $transferModel->status = 'failed';
        $transferModel->message = $msg;
        $transferModel->save();
        $this->repository->commit();
    }

    private function buildResponse(string $status, int $code, string $message, bool $transferred, bool $notified, TransferInput $input): array
    {
        return [
            'status' => $status,
            'code' => $code,
            'message' => $message,
            'transferred' => $transferred,
            'notified' => $notified,
            'payer_balance' => $this->repository->getBalance($input->payer),
            'payee_balance' => $this->repository->getBalance($input->payee),
        ];
    }

    private function formatErrorMessage(string $msg): string
    {
        if (str_contains(strtolower($msg), 'api') || str_contains(strtolower($msg), 'external')) {
            return 'Transferência não realizada devido a erro de comunicação com serviço externo.';
        }
        if (str_contains(strtolower($msg), 'saldo insuficiente')) {
            return 'Saldo insuficiente para realizar a transferência.';
        }
        return $msg;
    }

    private function debitPayerWallet(TransferInput $input): void
    {
        $payer = \App\Model\User::find($input->payer);
        if (! $payer || ! $payer->wallet_id) {
            throw new \DomainException('Carteira do pagador não encontrada.');
        }
        $payerWallet = \App\Model\Wallet::find($payer->wallet_id);
        if (! $payerWallet) {
            throw new \DomainException('Carteira do pagador não encontrada.');
        }
        $value = (float) $input->value;
        $payerWallet->balance = (float) $payerWallet->balance - $value;
        $payerWallet->save();
    }

    private function creditPayeeWallet(TransferInput $input): void
    {
        $payee = \App\Model\User::find($input->payee);
        if (! $payee || ! $payee->wallet_id) {
            throw new \DomainException('Carteira do recebedor não encontrada.');
        }
        $payeeWallet = \App\Model\Wallet::find($payee->wallet_id);
        if (! $payeeWallet) {
            throw new \DomainException('Carteira do recebedor não encontrada.');
        }
        $value = (float) $input->value;
        $payeeWallet->balance = (float) $payeeWallet->balance + $value;
        $payeeWallet->save();
    }
}
