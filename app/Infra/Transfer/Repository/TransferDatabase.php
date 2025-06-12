<?php

namespace App\Infra\Transfer\Repository;

use App\Domain\Transfer\Port\Out\TransferRepositoryInterface;
use App\Domain\Transfer\InputOutputData\TransferInput;
use App\Domain\Transfer\Entity\UserType;
use App\Model\User;
use App\Model\Wallet;

class TransferDatabase implements TransferRepositoryInterface
{
    public function save(TransferInput $input): void
    {
        \App\Model\Transfer::create([
            'payer_id' => $input->payer,
            'payee_id' => $input->payee,
            'value' => $input->value,
            'status' => 'pending',
            'message' => null,
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
        $payer = User::findOrFail($input->payer);
        $payerWallet = Wallet::findOrFail($payer->wallet_id);
        $payerWallet->balance -= $input->value;
        $payerWallet->save();
        // Credita no payee
        $payee = User::findOrFail($input->payee);
        $payeeWallet = Wallet::findOrFail($payee->wallet_id);
        $payeeWallet->balance += $input->value;
        $payeeWallet->save();
    }
    public function getUserType(int $userId): UserType
    {
        $user = User::findOrFail($userId);
        return match ($user->type_person) {
            'personal' => UserType::PERSONAL,
            'business' => UserType::BUSINESS,
            default => throw new \Exception('Tipo de usuário desconhecido')
        };
    }
    public function getBalance(int $userId): float
    {
        $user = User::findOrFail($userId);
        return (float) $user->wallet->balance;
    }
}
