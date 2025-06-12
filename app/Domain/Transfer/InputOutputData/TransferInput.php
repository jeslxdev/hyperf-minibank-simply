<?php

namespace App\Domain\Transfer\InputOutputData;

use App\Infra\Transfer\Validator\ValidatorFactory;
use App\Infra\Transfer\Validator\AppValidationException;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema]
class TransferInput
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

    /**
     * @throws AppValidationException
     */
    private function validationInputData(array $data): void
    {
        $validator = ValidatorFactory::create($data);
        $validator->mapFieldsRules([
            'value' => ['required', 'numeric'],
            'payer' => ['required', 'integer'],
            'payee' => ['required', 'integer'],
        ]);
        $validator->validate(true);
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
