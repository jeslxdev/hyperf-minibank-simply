<?php

namespace App\Domain\Transfer\DTO;

use OpenApi\Attributes\Property;
use App\Infra\Transfer\Validator\ValidatorFactory;

class TransferDTO
{
    #[Property]
    public int $payer_id;
    #[Property]
    public int $payee_id;
    #[Property]
    public float $value;
    #[Property]
    public string $status;
    #[Property]
    public ?string $message;
    #[Property]
    public string $created_at;
    #[Property]
    public string $updated_at;

    public function __construct(array $data)
    {
        $this->validationInputData($data);
        $this->payer_id = $data['payer_id'];
        $this->payee_id = $data['payee_id'];
        $this->value = $data['value'];
        $this->status = $data['status'] ?? 'pending';
        $this->message = $data['message'] ?? null;
        $this->created_at = $data['created_at'] ?? date('Y-m-d H:i:s');
        $this->updated_at = $data['updated_at'] ?? date('Y-m-d H:i:s');
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
            'payer_id' => ['required', 'integer'],
            'payee_id' => ['required', 'integer'],
            'status' => ['string', 'in:pending,success,failed,reversed'],
            'message' => ['nullable', 'string'],
            'created_at' => ['date_format:Y-m-d H:i:s'],
            'updated_at' => ['date_format:Y-m-d H:i:s'],
        ]);
    }
}
