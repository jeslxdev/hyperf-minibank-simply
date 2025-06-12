<?php

namespace App\Domain\Transfer\Port\Out;

use App\Domain\User\Entity\Transfer;


interface TransferModelInterface
{
    public function create(array $data): mixed;
    public function find(int $id): mixed;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
