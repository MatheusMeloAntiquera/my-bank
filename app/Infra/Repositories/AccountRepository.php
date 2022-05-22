<?php

namespace App\Infra\Repositories;

use DateTime;
use Exception;
use App\Domain\Entity\Account;
use Illuminate\Support\Facades\DB;
use App\Domain\Repositories\AccountRepositoryInterface;

class AccountRepository implements AccountRepositoryInterface
{
    private $table = 'accounts';
    public function findById(int $id): ?Account
    {
        $result = DB::table($this->table)
            ->where('id', $id)->first();

        if (empty($result)) {
            return null;
        }

        return new Account(
            id: $result->id,
            balance: $result->balance,
            active: $result->active,
            created_at: $result->created_at == null ? null : new DateTime($result->created_at),
            updated_at: $result->updated_at == null ? null : new DateTime($result->updated_at),
        );
    }

    public function create(Account $account): Account
    {
        $createdAt = new DateTime("now");
        $account->id = DB::table($this->table)->insertGetId(
            [
                'balance' => $account->balance,
                'active' => $account->active,
                'created_at' => $createdAt,
                'updated_at' => null,
            ]
        );

        $account->createdAt = $createdAt;
        return $account;
    }

    public function findOrCreate(?int $accountId): Account
    {
        $account = $this->findById($accountId);
        if (!empty($account)) {
            return $account;
        }

        $newAccount = new Account(
            id: $accountId,
            created_at: new DateTime("now")
        );

        DB::table($this->table)->insert(
            [
                'id' => $accountId,
                'balance' => $newAccount->balance,
                'active' => $newAccount->active,
                'created_at' => $newAccount->created_at,
                'updated_at' => null,
            ]
        );

        return $newAccount;
    }

    public function updateBalance(Account $account, float $newBalance): Account
    {
        $affected = DB::table($this->table)
            ->where('id', $account->id)
            ->update(['balance' => $newBalance]);
        if ($affected != 1) {
            throw new Exception("It was not possible update the balance");
        }

        $account->balance = $newBalance;
        return $account;
    }
}
