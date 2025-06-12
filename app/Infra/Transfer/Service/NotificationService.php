<?php

namespace App\Infra\Transfer\Service;

use App\Domain\Transfer\Port\Out\NotificationServiceInterface;
use GuzzleHttp\Client;

class NotificationService implements NotificationServiceInterface
{
    public function notify(array $payload): bool
    {
        $client = new Client();
        try {
            $response = $client->post('https://util.devi.tools/api/v1/notify', [
                'json' => $payload,
            ]);
            if (in_array($response->getStatusCode(), [200, 204])) {
                $payee = $payload['payee_name'] ?? $payload['payee'] ?? 'usuário';
                $value = $payload['value'] ?? 0;
                $smsMessage = "[SIMULADO] SMS enviado para {$payee}: Você recebeu uma transferência PIX de R$ {$value}.";
                var_dump(value: $smsMessage);
                return true;
            }
        } catch (\Throwable $e) {
            return false;
        }
    }
}
