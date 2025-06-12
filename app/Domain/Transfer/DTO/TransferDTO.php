<?php

namespace App\Domain\Transfer\DTO;

use OpenApi\Attributes\Property;
use App\Infra\Transfer\Validator\ValidatorFactory;

class TransferDTO
{
    #[Property]
    public float $value;
    #[Property]
    public int $payer;
    #[Property]
    public int $payee;

    public function __construct(array $data)
    {
        $this->validationInputData($data);
        $this->value = $data['value'];
        $this->payer = $data['payer'];
        $this->payee = $data['payee'];
    }

    public static function create(array $data): static
    {
        return new self($data);
    }

    public static function validationInputData(array $data): void
    {
        $validator = ValidatorFactory::create($data);
        $validator->mapFieldsRules([
            'value' => ['required', 'numeric'],
            'payer' => ['required', 'integer'],
            'payee' => ['required', 'integer'],
        ]);
    }
}
