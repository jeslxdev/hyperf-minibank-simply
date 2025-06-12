<?php

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

class User extends Model
{
    protected ?string $table = 'users';
    protected array $fillable = [
        'full_name',
        'cpf_cnpj',
        'email',
        'password',
        'type_person',
        'wallet_id',
        'created_at',
        'updated_at',
    ];
    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id');
    }
}
