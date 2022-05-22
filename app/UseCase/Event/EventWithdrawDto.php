<?php

namespace App\UseCase\Event;

class EventWithdrawDto
{
    private int $originId;
    private float $newBalance;

    public function __construct(int $originId, float $newBalance)
    {
        $this->originId = $originId;
        $this->newBalance = $newBalance;
    }

    public function __toArray()
    {
        return [
            "origin" => [
                "id" => $this->originId,
                "balance" => $this->newBalance
            ]
        ];
    }
}
