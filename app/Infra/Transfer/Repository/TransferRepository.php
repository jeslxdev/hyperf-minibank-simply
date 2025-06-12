<?php

namespace App\Infra\Transfer\Repository;

use App\Domain\Transfer\Port\Out\TransferRepositoryInterface;
use App\Domain\Transfer\InputOutputData\TransferInput;
use App\Domain\Transfer\Entity\UserType;

class TransferRepository implements TransferRepositoryInterface
{
    public function save(TransferInput $input): void
    {
        \App\Model\Transfer::create([
            'payer_id' => $input->payer,
            'payee_id' => $input->payee,
            'value' => $input->value,
            'status' => 'pending',
            'message' => null
        ]);
    }
    public function beginTransaction(): void
    {
        \Hyperf\DbConnection\Db::beginTransaction();
    }
    public function commit(): void
    {
        \Hyperf\DbConnection\Db::commit();
    }
    public function rollback(): void
    {
        \Hyperf\DbConnection\Db::rollBack();
    }
    public function transfer(TransferInput $input): void
    {
        // Debita do payer
        $payerWalletId = \Hyperf\DbConnection\Db::table('users')->where('id', $input->payer)->value('wallet_id');
        \Hyperf\DbConnection\Db::table('wallet')->where('id', $payerWalletId)->decrement('balance', $input->value);
        // Credita no payee
        $payeeWalletId = \Hyperf\DbConnection\Db::table('users')->where('id', $input->payee)->value('wallet_id');
        \Hyperf\DbConnection\Db::table('wallet')->where('id', $payeeWalletId)->increment('balance', $input->value);
    }
    public function getUserType(int $userId): UserType
    {
        $type = \Hyperf\DbConnection\Db::table('users')->where('id', $userId)->value('type_person');
        return match ($type) {
            'personal' => UserType::PERSONAL,
            'business' => UserType::BUSINESS,
            default => throw new \Exception('Tipo de usuário desconhecido')
        };
    }
    public function getBalance(int $userId): float
    {
        // Buscar saldo do usuário no banco
        // Exemplo mock:
        return 1000.0;
    }
}
