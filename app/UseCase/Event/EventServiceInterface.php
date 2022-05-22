<?php

namespace App\UseCase\Event;

use App\UseCase\Event\EventDepositDto;
use App\UseCase\Event\EventWithdrawDto;

interface EventServiceInterface {
    public function deposit(int $destinationId, float $amount): EventDepositDto;
    public function withdraw(int $destinationId, float $amount): EventWithdrawDto|int;
}
