<?php

namespace App\Swagger\Attributes\Response;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class ResponseBadRequest extends ResponseException
{
    public function __construct()
    {
        parent::__construct(400, 'Requisição inválida');
    }
}
