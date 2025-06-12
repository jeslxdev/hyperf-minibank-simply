<?php

namespace App\Infra\Transfer\Repository;

use App\Domain\Transfer\Port\Out\TransferRepositoryInterface;
use App\Domain\Transfer\InputOutputData\TransferInput;
use App\Domain\Transfer\Entity\UserType;

class TransferMemory implements TransferRepositoryInterface
{
    private array $users = [
        1 => ['type_person' => 'personal', 'wallet_id' => 1],
        2 => ['type_person' => 'business', 'wallet_id' => 2],
    ];
    private array $wallets = [
        1 => ['balance' => 1000.0],
        2 => ['balance' => 5000.0],
    ];
    private array $transfers = [];
    public function save(TransferInput $input): void
    {
        $this->transfers[] = [
            'payer_id' => $input->payer,
            'payee_id' => $input->payee,
            'value' => $input->value,
            'status' => 'pending',
            'message' => null,
        ];
    }
    public function beginTransaction(): void
    {
        // Simula início de transação (não faz nada em memória)
    }
    public function commit(): void
    {
        // Simula commit de transação (não faz nada em memória)
    }
    public function rollback(): void
    {
        // Simula rollback de transação (não faz nada em memória)
    }
    public function transfer(TransferInput $input): void
    {
        $payerWallet = $this->users[$input->payer]['wallet_id'];
        $payeeWallet = $this->users[$input->payee]['wallet_id'];
        $this->wallets[$payerWallet]['balance'] -= $input->value;
        $this->wallets[$payeeWallet]['balance'] += $input->value;
    }
    public function getUserType(int $userId): UserType
    {
        $type = $this->users[$userId]['type_person'] ?? null;
        return match ($type) {
            'personal' => UserType::PERSONAL,
            'business' => UserType::BUSINESS,
            default => throw new \Exception('Tipo de usuário desconhecido')
        };
    }
    public function getBalance(int $userId): float
    {
        $walletId = $this->users[$userId]['wallet_id'] ?? null;
        return $walletId ? $this->wallets[$walletId]['balance'] : 0.0;
    }
}
