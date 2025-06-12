<?php

namespace App\Domain\User\Port\Out;

use App\Domain\User\Entity\User;

interface UserRepositoryInterface
{
    public function find(int $userId): ?User;
    public function create(array $data): User;
    public function update(int $userId, array $data): bool;
    public function delete(int $userId): bool;
}
