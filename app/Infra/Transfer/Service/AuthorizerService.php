<?php

namespace App\Infra\Transfer\Service;

use App\Domain\Transfer\Port\Out\AuthorizerServiceInterface;
use GuzzleHttp\Client;

class AuthorizerService implements AuthorizerServiceInterface
{
    public function authorize(): bool
    {
        $client = new Client();
        $response = $client->get('https://util.devi.tools/api/v2/authorize');
        $data = json_decode($response->getBody()->getContents(), true);
        return isset(
            $data['status'],
            $data['data']['authorization']
        ) &&
            $data['status'] === 'success' &&
            $data['data']['authorization'] === true;
    }
}
