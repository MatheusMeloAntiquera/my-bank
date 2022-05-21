<?php

namespace App\Application\Repositories;

use App\Domain\Entity\Account;

interface AccountRepositoryInterface
{
    public function findById(int $id): ?Account;
}
