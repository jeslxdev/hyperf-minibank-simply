<?php

use App\Domain\Transfer\Port\Out\TransferRepositoryInterface;
use App\Domain\Transfer\Port\Out\AuthorizerServiceInterface;
use App\Domain\Transfer\Port\Out\NotificationServiceInterface;
use App\Infra\Transfer\Repository\TransferRepositoryInDatabase;
use App\Infra\Transfer\Service\AuthorizerService;
use App\Infra\Transfer\Service\NotificationService;


return [
    TransferRepositoryInterface::class => TransferRepositoryInDatabase::class,
    AuthorizerServiceInterface::class => AuthorizerService::class,
    NotificationServiceInterface::class => NotificationService::class,
];
