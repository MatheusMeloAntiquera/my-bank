<?php

namespace App\Domain\Repositories;

use App\Domain\Entity\Event;

interface EventRepositoryInterface
{
    public function create(Event $event): ?Event;
}
