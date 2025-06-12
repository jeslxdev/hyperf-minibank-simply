<?php

namespace App\Domain\Transfer\Entity;

class Transfer
{
    public int $id;
    public int $payer_id;
    public int $payee_id;
    public float $value;
    public string $status;
    public ?string $message;
    public string $created_at;
    public string $updated_at;

    public function __construct(
        int $id,
        int $payer_id,
        int $payee_id,
        float $value,
        string $status,
        ?string $message,
        string $created_at,
        string $updated_at
    ) {
        $this->id = $id;
        $this->payer_id = $payer_id;
        $this->payee_id = $payee_id;
        $this->value = $value;
        $this->status = $status;
        $this->message = $message;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
}
