<?php

namespace App\Domain\Transfer\UseCase;

use App\Domain\Transfer\InputOutputData\TransferInput;
use App\Domain\Transfer\Port\Out\AuthorizerServiceInterface;
use App\Domain\Transfer\Port\Out\NotificationServiceInterface;
use App\Infra\Transfer\Service\TransferService;
use App\Domain\Transfer\Entity\UserType;
use App\Domain\Transfer\Port\Out\TransferRepositoryInterface;

class TransferUseCase
{
    public function __construct(
        private TransferService $transferService,
        private TransferRepositoryInterface $repository,
        private AuthorizerServiceInterface $authorizer,
        private NotificationServiceInterface $notifier
    ) {
    }

    public function execute(TransferInput $input): array
    {
        $payerType = $this->repository->getUserType($input->payer);
        if ($payerType === UserType::BUSINESS) {
            return [
                'status' => 'error',
                'code' => 403,
                'message' => 'Lojistas não podem enviar transferências.'
            ];
        }
        return $this->transferService->execute($input);
    }
}
