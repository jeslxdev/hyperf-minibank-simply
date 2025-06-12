<?php

namespace App\Infra\Transfer\Validator;

use InvalidArgumentException;

class AppValidationException extends InvalidArgumentException
{
    public function __construct(array $message)
    {
        $jsonString = json_encode($message);
        parent::__construct($jsonString);
    }
}
