<?php

namespace App\Swagger\Attributes\Response;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
abstract class ResponseException
{
    public int $status;
    public string $description;

    public function __construct(int $status, string $description)
    {
        $this->status = $status;
        $this->description = $description;
    }
}
