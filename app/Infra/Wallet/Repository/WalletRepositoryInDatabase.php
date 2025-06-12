<?php

namespace App\Infra\Wallet\Repository;

use App\Domain\Wallet\Entity\Wallet;
use App\Domain\Wallet\Port\Out\WalletRepositoryInterface;
use App\Model\Wallet as WalletModel;

class WalletRepositoryInDatabase implements WalletRepositoryInterface
{
    public function find(int $walletId): ?Wallet
    {
        $model = WalletModel::find($walletId);
        if (!$model) {
            return null;
        }
        return new Wallet($model->id, (float)$model->balance);
    }

    public function create(array $data): Wallet
    {
        $model = WalletModel::create($data);
        return $this->find($model->id);
    }

    public function update(int $walletId, array $data): bool
    {
        $model = WalletModel::find($walletId);
        if (!$model) {
            return false;
        }
        $model->fill($data);
        return $model->save();
    }

    public function delete(int $walletId): bool
    {
        $model = WalletModel::find($walletId);
        if (!$model) {
            return false;
        }
        return $model->delete();
    }
}
