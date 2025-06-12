<?php

namespace App\Domain\Transfer\Port\Out;

use App\Domain\Transfer\InputOutputData\TransferInput;
use App\Domain\Transfer\Entity\Transfer;

interface TransferRepositoryInterface
{
    public function save(TransferInput $input): void;
    public function beginTransaction(): void;
    public function commit(): void;
    public function rollback(): void;
    public function transfer(TransferInput $input): void;
    public function getUserType(int $userId): string;
    public function getBalance(int $userId): float;
    public function update(int $id, array $data): bool;
    public function create(array $data): Transfer;
    public function find(int $id): ?Transfer;
    public function delete(int $id): bool;
}
