<?php

namespace App\Infra\Transfer\Repository;

use App\Domain\Transfer\Entity\Transfer;
use App\Domain\Transfer\Port\Out\TransferModelInterface;
use App\Domain\User\Port\Out\UserRepositoryInterface;
use App\Domain\Wallet\Port\Out\WalletRepositoryInterface;
class TransferRepositoryInMemory implements TransferModelInterface
{
    private array $transfers = [];
    private int $autoIncrement = 1;

    public function __construct(
        private UserRepositoryInterface $userRepository,
        private WalletRepositoryInterface $walletRepository
    ) {
    }

    public function create(array $data): Transfer
    {
        $transfer = new Transfer(
            $this->autoIncrement++,
            $data['payer_id'],
            $data['payee_id'],
            $data['value'],
            $data['status'],
            $data['message'] ?? null,
            $data['created_at'] ?? date('Y-m-d H:i:s'),
            $data['updated_at'] ?? date('Y-m-d H:i:s')
        );
        $this->transfers[$transfer->id] = $transfer;
        return $transfer;
    }

    public function find(int $id): ?Transfer
    {
        return $this->transfers[$id] ?? null;
    }

    public function update(int $id, array $data): bool
    {
        if (!isset($this->transfers[$id])) {
            return false;
        }
        foreach ($data as $key => $value) {
            if (property_exists($this->transfers[$id], $key)) {
                $this->transfers[$id]->$key = $value;
            }
        }
        $this->transfers[$id]->updated_at = date('Y-m-d H:i:s');
        return true;
    }

    public function delete(int $id): bool
    {
        if (!isset($this->transfers[$id])) {
            return false;
        }
        unset($this->transfers[$id]);
        return true;
    }

    public function save(\App\Domain\Transfer\InputOutputData\TransferInput $input): void
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
        // In-memory: não faz nada
    }

    public function commit(): void
    {
        // In-memory: não faz nada
    }

    public function rollback(): void
    {
        // In-memory: não faz nada
    }

    public function transfer(\App\Domain\Transfer\InputOutputData\TransferInput $input): void
    {
        // Simula transferência em memória
    }

    public function getUserType(int $userId): string
    {
        // Simula tipo de usuário
        return 'personal';
    }

    public function getBalance(int $userId): float
    {
        // Simula saldo
        return 0.0;
    }
}
