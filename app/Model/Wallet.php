<?php

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

class Wallet extends Model
{
    protected ?string $table = 'wallet';
    protected array $fillable = [
        'balance',
        'number',
        'type',
        'currency',
        'active',
        'last_transaction_at',
        'created_at',
        'updated_at',
    ];
    public function user()
    {
        return $this->hasOne(User::class, 'wallet_id');
    }
}
