<?php

declare(strict_types=1);

namespace App\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Hyperf\HttpServer\Annotation\Middleware;
use Psr\Http\Message\ResponseInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HyperfResponseInterface;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

#[Middleware(JwtAuthMiddleware::class)]
class JwtAuthMiddleware implements \Psr\Http\Server\MiddlewareInterface
{
    #[Inject]
    protected HyperfResponseInterface $response;

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $authHeader = $request->getHeaderLine('Authorization');
        if (! $authHeader || ! str_starts_with($authHeader, 'Bearer ')) {
            return $this->response->json([
                'error' => 'Token JWT não informado',
            ])->withStatus(401);
        }
        $token = substr($authHeader, 7);
        try {
            JWT::decode($token, new Key(getenv('JWT_SECRET') ?: 'secret', 'HS256'));
            return $handler->handle($request);
        } catch (\Throwable $e) {
            return $this->response->json([
                'error' => 'Token JWT inválido ou expirado',
            ])->withStatus(401);
        }
    }
}
