<?php

namespace App\Controller\Transfers\V1;

use Fig\Http\Message\StatusCodeInterface;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\Swagger\Annotation as OA;
use App\Controller\AbstractController;
use App\Domain\Transfer\UseCase\TransferUseCase;
use App\Domain\Transfer\InputOutputData\TransferInput;
use App\Domain\Transfer\InputOutputData\TransferOutput;
use App\Swagger\Attributes\Request\RequestBodyJson;
use App\Swagger\Attributes\Response\ResponseJson;
use App\Swagger\Attributes\Response\ResponseServerError;
use App\Swagger\Attributes\Response\ResponseNotFound;
use App\Swagger\Attributes\Response\ResponseBadRequest;

#[OA\HyperfServer('php-minibank-hyperf-us-1')]
#[Controller('/transfer/v1')]
#[OA\SecurityScheme(securityScheme: 'BearerAuth', type: 'http', scheme: 'bearer', name: 'Authorization', in: 'header')]
class TransferV1Controller extends AbstractController
{
    public function __construct(
        private TransferUseCase $transferUseCase,
    ) {
    }

    #[PostMapping('transfer')]
    #[OA\Post(
        path: '/transfer/v1/transfer',
        description: 'Realiza uma transferência entre usuários',
        tags: ['transfer'],
        security: [['BearerAuth' => []]],
        summary: 'Transferência entre usuários',
    )]
    #[OA\Response(
        response: StatusCodeInterface::STATUS_BAD_REQUEST,
        description: 'Erro ao tentar efetuar a transferência',
    )]
    #[RequestBodyJson(TransferInput::class)]
    #[ResponseJson(TransferOutput::class, StatusCodeInterface::STATUS_OK)]
    #[Middleware(\App\Middleware\JwtAuthMiddleware::class)]
    #[ResponseServerError]
    #[ResponseNotFound]
    #[ResponseBadRequest]

    public function transfer(): array
    {
        $request = $this->request;
        return $this->transferUseCase->execute(
            TransferInput::create($request->all())
        );
    }
}
