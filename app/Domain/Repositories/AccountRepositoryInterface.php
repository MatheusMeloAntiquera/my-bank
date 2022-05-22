<?php

namespace App\Domain\Repositories;

use App\Domain\Entity\Account;

interface AccountRepositoryInterface
{
    public function findById(int $id): ?Account;
    public function create(Account $account): Account;
    public function findOrCreate(?int $accountId): Account;
    public function updateBalance(Account $account, float $newBalance): Account;
}
