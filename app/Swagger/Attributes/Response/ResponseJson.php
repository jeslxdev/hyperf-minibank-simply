<?php

namespace App\Swagger\Attributes\Response;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class ResponseJson
{
    public string $ref;
    public int $status;

    public function __construct(string $ref, int $status = 200)
    {
        $this->ref = $ref;
        $this->status = $status;
    }
}
