<?php

namespace App\Infra\Transfer\Data;

class DTO
{
    public function __construct(array $data = [])
    {
    }

    public static function create(array $data): static
    {
        return new static($data);
    }
}
