<?php

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

class Transfer extends Model
{
    protected ?string $table = 'transfers';
    protected array $fillable = [
        'payer_id',
        'payee_id',
        'value',
        'status',
        'message',
        'created_at',
        'updated_at',
    ];

    public function payer()
    {
        return $this->belongsTo(User::class, 'payer_id');
    }

    public function payee()
    {
        return $this->belongsTo(User::class, 'payee_id');
    }
}
