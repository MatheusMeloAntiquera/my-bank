<?php

namespace App\Domain\Repositories;

use App\Domain\Entity\User;

interface UserRepositoryInterface
{
    public function create(User $user): User;
    public function findById(int $id): ?User;
}
