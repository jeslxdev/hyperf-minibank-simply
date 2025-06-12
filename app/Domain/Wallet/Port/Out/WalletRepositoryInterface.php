<?php

namespace App\Domain\Wallet\Port\Out;

use App\Domain\Wallet\Entity\Wallet;

interface WalletRepositoryInterface
{
    public function find(int $walletId): ?Wallet;
    public function create(array $data): Wallet;
    public function update(int $walletId, array $data): bool;
    public function delete(int $walletId): bool;
}
