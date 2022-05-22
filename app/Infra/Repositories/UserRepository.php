<?php

namespace App\Domain\Repositories;

use DateTime;
use App\Domain\Entity\User;
use Illuminate\Support\Facades\DB;
use App\Domain\Repositories\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    private $table = 'users';
    public function findById(int $id): User
    {
        $result = DB::table($this->table)
            ->where('id', $id)->first();

        if (empty($result)) {
            return null;
        }

        return $this->createUserEntity($result);
    }

    public function create(User $user): User
    {
        $createdAt = new DateTime("now");
        $id = DB::table($this->table)->insertGetId(
            [
                'name' => $user->name,
                'email' => $user->email,
                'active' => $user->active,
                'account_id' => $user->account_id,
                'created_at' => $createdAt,
                'updated_at' => null,
            ]
        );

        $user->id = $id;
        $user->created_at = $createdAt;

        return $user;
    }

    private function createUserEntity(object $result)
    {
        return new User(
            id: $result->id,
            name: $result->name,
            email: $result->email,
            active: $result->active,
            account_id: $result->account_id,
            created_at: $result->created_at,
            updated_at: $result->updated_at,
        );
    }
}
