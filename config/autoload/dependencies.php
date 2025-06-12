<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
use App\Domain\User\Port\Out\UserRepositoryInterface;
use App\Domain\Wallet\Port\Out\WalletRepositoryInterface;
use App\Infra\User\Repository\UserRepositoryInDatabase;
use App\Infra\Wallet\Repository\WalletRepositoryInDatabase;
use App\Domain\Transfer\Port\Out\TransferRepositoryInterface;
use App\Domain\Transfer\Port\Out\AuthorizerServiceInterface;
use App\Infra\Transfer\Service\AuthorizerService;
use App\Domain\Transfer\Port\Out\NotificationServiceInterface;
use App\Infra\Transfer\Service\NotificationService;
use App\Domain\Transfer\Port\Out\TransferModelInterface;
use App\Infra\Transfer\Repository\TransferRepositoryInDatabase;

return [
    UserRepositoryInterface::class => UserRepositoryInDatabase::class,
    WalletRepositoryInterface::class => WalletRepositoryInDatabase::class,
    TransferRepositoryInterface::class => TransferRepositoryInDatabase::class,
    AuthorizerServiceInterface::class => AuthorizerService::class,
    NotificationServiceInterface::class => NotificationService::class,
    TransferModelInterface::class => TransferRepositoryInDatabase::class,
];
