<?php

declare(strict_types=1);

namespace App\Domain\Auth\UseCase;

use Firebase\JWT\JWT;
use App\Model\User;

class AuthUseCase
{
    public function execute(array $data): array
    {
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;
        $user = User::where('email', $email)->first();
        if (! $user || ! password_verify($password, $user->password)) {
            return ['error' => 'Credenciais inválidas'];
        }
        $payload = [
            'sub' => $user->id,
            'email' => $user->email,
            'iat' => time(),
            'exp' => time() + 3600,
        ];
        $jwt = JWT::encode($payload, getenv('JWT_SECRET') ?: 'secret', 'HS256');
        return ['token' => $jwt];
    }
}
