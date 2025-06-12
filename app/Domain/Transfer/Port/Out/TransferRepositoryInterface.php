<?php

namespace App\Domain\Transfer\Port\Out;

use App\Domain\Transfer\Entity\UserType;
use App\Domain\Transfer\InputOutputData\TransferInput;

interface TransferRepositoryInterface
{
    public function save(TransferInput $input): void;
    public function beginTransaction(): void;
    public function commit(): void;
    public function rollback(): void;
    public function transfer(TransferInput $input): void;
    public function getUserType(int $userId): UserType;
    public function getBalance(int $userId): float;
}
