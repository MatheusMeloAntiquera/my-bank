<?php

namespace App\UseCase\Event;

use App\UseCase\Event\EventDepositDto;
use App\UseCase\Event\EventTransferDto;
use App\UseCase\Event\EventWithdrawDto;

interface EventServiceInterface
{
    public function deposit(int $destinationId, float $amount): EventDepositDto;
    public function withdraw(int $originId, float $amount): EventWithdrawDto|int;
    public function transfer(int $originId, int $destinationId, float $amount): EventTransferDto|int;
}
