<?php

namespace App\Swagger\Attributes\Request;

use Attribute;
use Hyperf\Swagger\Annotation\JsonContent;
use Hyperf\Swagger\Annotation\RequestBody;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class RequestBodyJson extends RequestBody
{
    public function __construct(string $ref = null)
    {
        parent::__construct(content: new JsonContent(ref: $ref));
    }
}
