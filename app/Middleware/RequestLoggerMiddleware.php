<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Log\LoggerInterface;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;

class RequestLoggerMiddleware implements MiddlewareInterface
{
    #[Inject]
    protected LoggerInterface $logger;

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $method = $request->getMethod();
        $uri = (string) $request->getUri();
        $body = $request->getParsedBody();
        $this->logger->info('Request recebido', [
            'method' => $method,
            'uri' => $uri,
            'body' => $body,
        ]);
        return $handler->handle($request);
    }
}
