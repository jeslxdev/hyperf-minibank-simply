<?php

declare(strict_types=1);

namespace App\Controller\AuthJWT\V1;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\PostMapping;
use App\Domain\Auth\UseCase\AuthUseCase;
use Hyperf\Swagger\Annotation as OA;
use App\Swagger\Attributes\Response\ResponseServerError;
use App\Swagger\Attributes\Response\ResponseNotFound;
use App\Swagger\Attributes\Response\ResponseBadRequest;
use App\Controller\AbstractController;
use Fig\Http\Message\StatusCodeInterface;

#[OA\HyperfServer('php-minibank-hyperf-us-1')]
#[Controller('/auth/v1')]
#[OA\SecurityScheme(securityScheme: 'BearerAuth', type: 'http', scheme: 'bearer', name: 'Authorization', in: 'header')]
class AuthV1Controller extends AbstractController
{   
    public function __construct(private AuthUseCase $authUseCase)
    {
        parent::__construct();
    }
    #[PostMapping('token')]
    #[OA\Post(
        path: '/auth/v1/token',
        description: 'Realiza a autenticação do usuário',
        tags: ['auth'],
        summary: 'Autenticação do usuário',
    )]
    #[OA\Parameter(
        name: 'login',
        in: 'header',
        required: true,
        description: 'Login do Usuario',
        schema: new OA\Schema(type: 'string', example: 'usuario@email.com')
    )]
    #[OA\Parameter(
        name: 'password',
        in: 'header',
        required: true,
        description: 'Senha do Usuario',
        schema: new OA\Schema(type: 'string', example: '123456')
    )]
    #[OA\Response(
        response: StatusCodeInterface::STATUS_BAD_REQUEST,
        description: 'Erro ao tentar efetuar a autenticação',
    )]
    #[ResponseServerError]
    #[ResponseNotFound]
    #[ResponseBadRequest]
    public function login(): array
    {
        $login = $this->request->getHeaderLine('login');
        $password = $this->request->getHeaderLine('password');
        return $this->authUseCase->execute(['email' => $login, 'password' => $password]);
    }
}
