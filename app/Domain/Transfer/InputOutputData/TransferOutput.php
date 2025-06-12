<?php

namespace App\Domain\Transfer\InputOutputData;

use OpenApi\Attributes\Schema;
use OpenApi\Attributes\Property;
use Hyperf\Contract\Arrayable;

#[Schema]
class TransferOutput implements Arrayable
{
    #[Property]
    public float $value;
    #[Property]
    public int $payer;
    #[Property]
    public int $payee;
    #[Property]
    public string $status;
    #[Property]
    public ?string $message;

    public function __construct(array $data)
    {
        $this->value = $data['value'];
        $this->payer = $data['payer'];
        $this->payee = $data['payee'];
        $this->status = $data['status'] ?? 'success';
        $this->message = $data['message'] ?? null;
    }

    public static function create(array $data): static
    {
        return new self($data);
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
