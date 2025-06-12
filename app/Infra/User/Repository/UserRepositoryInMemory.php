<?php

namespace App\Infra\User\Repository;

use App\Domain\User\Entity\User;
use App\Domain\User\Port\Out\UserRepositoryInterface;

class UserRepositoryInMemory implements UserRepositoryInterface
{
    private array $users = [];
    public function __construct(array $users = [])
    {
        $this->users = $users;
    }
    public function find(int $userId): ?User
    {
        return $this->users[$userId] ?? null;
    }
    public function create(array $data): User
    {
        $id = count($this->users) + 1;
        $user = new User(
            $id,
            $data['full_name'] ?? '',
            $data['cpf_cnpj'] ?? '',
            $data['email'] ?? '',
            $data['password'] ?? '',
            $data['type_person'] ?? '',
            $data['wallet_id'] ?? null,
            $data['created_at'] ?? date('Y-m-d H:i:s'),
            $data['updated_at'] ?? date('Y-m-d H:i:s')
        );
        $this->users[$id] = $user;
        return $user;
    }

    public function update(int $userId, array $data): bool
    {
        if (!isset($this->users[$userId])) {
            return false;
        }
        foreach ($data as $key => $value) {
            if (property_exists($this->users[$userId], $key)) {
                $this->users[$userId]->$key = $value;
            }
        }
        $this->users[$userId]->updated_at = date('Y-m-d H:i:s');
        return true;
    }

    public function delete(int $userId): bool
    {
        if (!isset($this->users[$userId])) {
            return false;
        }
        unset($this->users[$userId]);
        return true;
    }
}
