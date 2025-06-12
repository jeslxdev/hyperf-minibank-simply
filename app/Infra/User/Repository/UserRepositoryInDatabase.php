<?php

namespace App\Infra\User\Repository;

use App\Domain\User\Entity\User;
use App\Domain\User\Port\Out\UserRepositoryInterface;
use App\Model\User as UserModel;

class UserRepositoryInDatabase implements UserRepositoryInterface
{
    public function find(int $userId): ?User
    {
        $model = UserModel::find($userId);
        if (!$model) {
            return null;
        }
        return new User($model->id, $model->wallet_id, $model->type_person);
    }

    public function create(array $data): User
    {
        $model = UserModel::create($data);
        return $this->find($model->id);
    }

    public function update(int $userId, array $data): bool
    {
        $model = UserModel::find($userId);
        if (!$model) {
            return false;
        }
        $model->fill($data);
        return $model->save();
    }

    public function delete(int $userId): bool
    {
        $model = UserModel::find($userId);
        if (!$model) {
            return false;
        }
        return $model->delete();
    }
}
