<?php

declare(strict_types=1);

namespace HyperfTest\Cases;

use App\Domain\Transfer\UseCase\TransferUseCase;
use App\Domain\Transfer\InputOutputData\TransferInput;
use App\Infra\Transfer\Service\TransferService;
use App\Infra\Transfer\Repository\TransferRepositoryInMemory;
use App\Domain\Transfer\Port\Out\AuthorizerServiceInterface;
use App\Domain\Transfer\Port\Out\NotificationServiceInterface;
use Firebase\JWT\JWT;
use Hyperf\Context\Context;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\TestCase;
use \App\Domain\Transfer\Entity\UserType;
use App\Infra\User\Repository\UserRepositoryInMemory;
use App\Infra\Wallet\Repository\WalletRepositoryInMemory;
use App\Domain\User\Entity\User as DomainUser;
use App\Domain\Wallet\Entity\Wallet as DomainWallet;

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
        $jwt = JWT::encode(['sub' => 1], getenv('JWT_SECRET') ?: 'secret', 'HS256');
        $request = (new ServerRequest())->withHeader('Authorization', 'Bearer ' . $jwt);
        Context::set(ServerRequestInterface::class, $request);
        $users = [
            1 => new DomainUser(1, 1, 'personal'),
            2 => new DomainUser(2, 2, 'business'),
        ];
        $wallets = [
            1 => new DomainWallet(1, 1000.0),
            2 => new DomainWallet(2, 5000.0),
        ];
        $userRepo = new UserRepositoryInMemory($users);
        $walletRepo = new WalletRepositoryInMemory($wallets);
        $transferRepo = new TransferRepositoryInMemory();
        $mockAuthorizer = $this->createMock(AuthorizerServiceInterface::class);
        $mockAuthorizer->method('authorize')->willReturn(true);
        $mockNotifier = $this->createMock(NotificationServiceInterface::class);
        $mockNotifier->method('notify')->willReturn(true);
        $service = new TransferService($transferRepo, $mockAuthorizer, $mockNotifier, $userRepo, $walletRepo);
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
        $jwt = JWT::encode(['sub' => 1], getenv('JWT_SECRET') ?: 'secret', 'HS256');
        $request = (new ServerRequest())->withHeader('Authorization', 'Bearer ' . $jwt);
        Context::set(ServerRequestInterface::class, $request);
        $users = [
            1 => new DomainUser(1, 1, 'personal'),
            2 => new DomainUser(2, 2, 'business'),
        ];
        $wallets = [
            1 => new DomainWallet(1, 1000.0),
            2 => new DomainWallet(2, 5000.0),
        ];
        $userRepo = new UserRepositoryInMemory($users);
        $walletRepo = new WalletRepositoryInMemory($wallets);
        $transferRepo = new TransferRepositoryInMemory();
        $mockAuthorizer = $this->createMock(AuthorizerServiceInterface::class);
        $mockAuthorizer->method('authorize')->willReturn(false);
        $mockNotifier = $this->createMock(NotificationServiceInterface::class);
        $mockNotifier->method('notify')->willReturn(true);
        $service = new TransferService($transferRepo, $mockAuthorizer, $mockNotifier, $userRepo, $walletRepo);
        $result = $service->execute($input);
        $this->assertEquals('reversed', $result['status']);
        $this->assertFalse($result['notified']);
    }
}
