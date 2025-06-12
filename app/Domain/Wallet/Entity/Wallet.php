<?php

namespace App\Domain\Wallet\Entity;

class Wallet
{
    public int $id;
    public float $balance;
    public string $number;
    public string $type;
    public string $currency;
    public bool $active;
    public ?string $last_transaction_at;
    public string $created_at;
    public string $updated_at;

    public function __construct(
        int $id,
        float $balance,
        string $number,
        string $type,
        string $currency,
        bool $active,
        ?string $last_transaction_at,
        string $created_at,
        string $updated_at
    ) {
        $this->id = $id;
        $this->balance = $balance;
        $this->number = $number;
        $this->type = $type;
        $this->currency = $currency;
        $this->active = $active;
        $this->last_transaction_at = $last_transaction_at;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
}
