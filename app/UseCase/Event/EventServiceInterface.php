<?php

namespace App\UseCase\Event;

use App\UseCase\Event\EventDepositDto;

interface EventServiceInterface {
    public function deposit(int $destinationId, float $amount): EventDepositDto;
}
