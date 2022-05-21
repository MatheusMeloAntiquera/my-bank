<?php

namespace App\Infra\Repositories;

use App\Domain\Entity\Account;
use Illuminate\Support\Facades\DB;
use App\Application\Repositories\AccountRepositoryInterface;

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
}
