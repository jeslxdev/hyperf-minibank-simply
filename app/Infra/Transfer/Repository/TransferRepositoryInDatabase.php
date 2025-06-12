<?php

namespace App\Infra\Transfer\Repository;

use App\Domain\Transfer\Entity\Transfer;
use App\Domain\Transfer\Port\Out\TransferModelInterface;
use App\Model\Transfer as TransferModel;
use App\Domain\Transfer\Port\In\TransferInput;
use App\Domain\User\Port\Out\UserRepositoryInterface;
use App\Domain\Wallet\Port\Out\WalletRepositoryInterface;

class TransferRepositoryInDatabase implements TransferModelInterface
{   

    public function __construct(
        private UserRepositoryInterface $userRepository,
        private WalletRepositoryInterface $walletRepository
    ) {
    }

    public function create(array $data): Transfer
    {
        $model = TransferModel::create($data);
        return new Transfer(
            $model->id,
            $model->payer_id,
            $model->payee_id,
            $model->value,
            $model->status,
            $model->message,
            $model->created_at,
            $model->updated_at
        );
    }

    public function find(int $id): ?Transfer
    {
        $model = TransferModel::find($id);
        if (!$model) {
            return null;
        }
        return new Transfer(
            $model->id,
            $model->payer_id,
            $model->payee_id,
            $model->value,
            $model->status,
            $model->message,
            $model->created_at,
            $model->updated_at
        );
    }

    public function update(int $id, array $data): bool
    {
        $model =  $this->userRepository->find($id);
        if (!$model) {
            return false;
        }
        $model->fill($data);
        $model->updated_at = date('Y-m-d H:i:s');
        return $model->save();
    }

    public function delete(int $id): bool
    {
        $model =  $this->userRepository->find($id);
        if (!$model) {
            return false;
        }
        return $model->delete();
    }

    public function save(TransferInput $input): void
    {
        $this->create([
            'payer_id' => $input->payer,
            'payee_id' => $input->payee,
            'value' => $input->value,
            'status' => 'pending',
            'message' => null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function beginTransaction(): void
    {
        \Hyperc\DbConnection\Db::beginTransaction();
    }

    public function commit(): void
    {
        \Hyperc\DbConnection\Db::commit();
    }

    public function rollback(): void
    {
        \Hyperc\DbConnection\Db::rollBack();
    }

    public function transfer(\App\Domain\Transfer\InputOutputData\TransferInput $input): void
    {
        // Debita do payer
        $payer = $this->userRepository->find($input->payer);
        $payerWallet = $this->walletRepository->find($payer->wallet_id);
        $payerWallet->balance -= $input->value;
        $payerWallet->save();
        // Credita no payee
        $payee = $this->userRepository->find($input->payee);
        $payeeWallet = $this->walletRepository->find($payee->wallet_id);
        $payeeWallet->balance += $input->value;
        $payeeWallet->update();
    }

    public function getUserType(int $userId): string
    {
        $user = $this->userRepository->find($userId);
        return $user->type_person;
    }

    public function getBalance(int $userId): float
    {
        $user = $this->userRepository->find($userId);
        return (float) $user->wallet_id->balance;
    }
}
