<?php

namespace App\Domain\Repositories;

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

        $account = new Account();
        $account->id = $result->id;
        $account->name = $result->name;
        $account->email = $result->email;
        $account->active = $result->active;
        $account->created_at = $result->created_at;
        $account->updated_at = $result->updated_at;

        return $account;
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
        return !empty($account) ? $account : $this->create(new Account());
    }

    public function updateBalance(Account $account, float $newBalance): Account
    {
        $affected = DB::table($this->table)
            ->where('id', $account->id)
            ->update(['balance' => $newBalance]);
        if($affected != 1){
            throw new Exception("It was not possible update the balance");
        }

        $account->balance = $newBalance;
        return $account;
    }
}
