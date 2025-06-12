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
return [
    App\Domain\Transfer\Port\Out\TransferRepositoryInterface::class => App\Infra\Transfer\Repository\TransferRepository::class,
    App\Domain\Transfer\Port\Out\AuthorizerServiceInterface::class => App\Infra\Transfer\Service\AuthorizerService::class,
    App\Domain\Transfer\Port\Out\NotificationServiceInterface::class => App\Infra\Transfer\Service\NotificationService::class,
];
