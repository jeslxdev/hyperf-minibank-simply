<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Log\LoggerInterface;
use Hyperf\Di\Annotation\Inject;

class RequestLoggerMiddleware implements \Psr\Http\Server\MiddlewareInterface
{
    #[Inject]
    protected LoggerInterface $logger;

    public function process(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Server\RequestHandlerInterface $handler): \Psr\Http\Message\ResponseInterface
    {
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
