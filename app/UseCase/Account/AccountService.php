<?php

namespace App\UseCase\Account;

use App\Domain\Entity\Account;
use App\UseCase\Account\AccountServiceInterface;
use App\Domain\Repositories\AccountRepositoryInterface;

class AccountService implements AccountServiceInterface
{
    private AccountRepositoryInterface $accountRepository;
    public function __construct(AccountRepositoryInterface $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function findByAccountId(int $accountId): ?Account
    {
        return $this->accountRepository->findById($accountId);
    }
}
