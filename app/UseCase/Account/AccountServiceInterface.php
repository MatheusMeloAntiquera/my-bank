<?php

namespace App\UseCase\Account;

use App\Domain\Entity\Account;

interface AccountServiceInterface {
    public function findByAccountId(int $accountId): Account|null;
}
