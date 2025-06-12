<?php

namespace App\Domain\Transfer\Port\Out;

interface NotificationServiceInterface
{
    public function notify(array $payload): bool;
}
