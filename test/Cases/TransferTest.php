<?php

declare(strict_types=1);

namespace HyperfTest\Cases;

use App\Domain\Transfer\UseCase\TransferUseCase;
use App\Domain\Transfer\InputOutputData\TransferInput;
use App\Infra\Transfer\Service\TransferService;
use App\Domain\Transfer\Port\Out\TransferRepositoryInterface;
use App\Domain\Transfer\Port\Out\AuthorizerServiceInterface;
use App\Domain\Transfer\Port\Out\NotificationServiceInterface;
use Firebase\JWT\JWT;
use Hyperf\Context\Context;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\TestCase;
use \App\Domain\Transfer\Entity\UserType;

class TransferTest extends TestCase
{
    public function testTransferUseCaseSuccess()
    {
        $input = new TransferInput([
            'payer' => 1,
            'payee' => 2,
            'value' => 100.0
        ]);
        $mockService = $this->createMock(TransferService::class);
        $mockRepo = $this->createMock(TransferRepositoryInterface::class);
        $mockRepo->method('getUserType')->willReturn(UserType::PERSONAL);
        $mockAuthorizer = $this->createMock(AuthorizerServiceInterface::class);
        $mockNotifier = $this->createMock(NotificationServiceInterface::class);
        $mockService->method('execute')->willReturn([
            'status' => 'success',
            'code' => 200,
            'message' => 'ok',
            'transferred' => true,
            'notified' => true,
            'payer_balance' => 900.0,
            'payee_balance' => 1100.0
        ]);
        $useCase = new TransferUseCase($mockService, $mockRepo, $mockAuthorizer, $mockNotifier);
        $output = $useCase->execute($input);
        $this->assertIsArray($output);
        $this->assertEquals('success', $output['status']);
    }

    public function testTransferServiceSuccess()
    {
        $input = new TransferInput([
            'payer' => 1,
            'payee' => 2,
            'value' => 100.0
        ]);
        // Gerar JWT válido para o payer
        $jwt = JWT::encode(['sub' => 1], getenv('JWT_SECRET') ?: 'secret', 'HS256');
        $request = (new ServerRequest())->withHeader('Authorization', 'Bearer ' . $jwt);
        Context::set(ServerRequestInterface::class, $request);
        $mockRepo = $this->createMock(TransferRepositoryInterface::class);
        $mockRepo->method('transfer');
        $mockRepo->method('beginTransaction');
        $mockRepo->method('commit');
        $mockRepo->method('save');
        $mockRepo->method('getBalance')->willReturn(900.0);
        $mockAuthorizer = $this->createMock(AuthorizerServiceInterface::class);
        $mockAuthorizer->method('authorize')->willReturn(true);
        $mockNotifier = $this->createMock(NotificationServiceInterface::class);
        $mockNotifier->method('notify')->willReturn(true);
        $service = new TransferService($mockRepo, $mockAuthorizer, $mockNotifier);
        $result = $service->execute($input);
        $this->assertEquals('success', $result['status']);
        $this->assertTrue($result['transferred']);
        $this->assertTrue($result['notified']);
    }

    public function testTransferServiceNotAuthorized()
    {
        $input = new TransferInput([
            'payer' => 1,
            'payee' => 2,
            'value' => 100.0
        ]);
        // Gerar JWT válido para o payer
        $jwt = JWT::encode(['sub' => 1], getenv('JWT_SECRET') ?: 'secret', 'HS256');
        $request = (new ServerRequest())->withHeader('Authorization', 'Bearer ' . $jwt);
        Context::set(ServerRequestInterface::class, $request);
        $mockRepo = $this->createMock(TransferRepositoryInterface::class);
        $mockRepo->method('transfer');
        $mockRepo->method('beginTransaction');
        $mockRepo->method('commit');
        $mockRepo->method('rollback');
        $mockRepo->method('save');
        $mockRepo->method('getBalance')->willReturn(900.0);
        $mockAuthorizer = $this->createMock(AuthorizerServiceInterface::class);
        $mockAuthorizer->method('authorize')->willReturn(false);
        $mockNotifier = $this->createMock(NotificationServiceInterface::class);
        $mockNotifier->method('notify')->willReturn(true);
        $service = new TransferService($mockRepo, $mockAuthorizer, $mockNotifier);
        $result = $service->execute($input);
        $this->assertEquals('reversed', $result['status']);
        $this->assertFalse($result['notified']);
    }
}
