<?php

namespace App\Domain\Transfer\Port\Out;

interface AuthorizerServiceInterface
{
    public function authorize(): bool;
}
