<?php

namespace App\Infra\Wallet\Repository;

use App\Domain\Wallet\Entity\Wallet;
use App\Domain\Wallet\Port\Out\WalletRepositoryInterface;

class WalletRepositoryInMemory implements WalletRepositoryInterface
{
    private array $wallets = [];
    public function __construct(array $wallets = [])
    {
        $this->wallets = $wallets;
    }
    public function find(int $walletId): ?Wallet
    {
        return $this->wallets[$walletId] ?? null;
    }

    public function create(array $data): Wallet
    {
        $wallet = new Wallet(...$data);
        $this->wallets[$wallet->getId()] = $wallet;
        return $wallet;
    }

    public function delete(int $walletId): bool
    {
        if (isset($this->wallets[$walletId])) {
            unset($this->wallets[$walletId]);
            return true;
        }
        return false;
    }

    public function update(int $walletId, array $data): bool
    {
        if (isset($this->wallets[$walletId])) {
            foreach ($data as $key => $value) {
                if (property_exists($this->wallets[$walletId], $key)) {
                    $this->wallets[$walletId]->$key = $value;
                }
            }
            return true;
        }
        return false;
    }
}
