<?php

namespace App\UseCase\Event;

class EventDepositDto
{
    private int $destinationId;
    private float $newBalance;

    public function __construct(int $destinationId, float $newBalance)
    {
        $this->destinationId = $destinationId;
        $this->newBalance = $newBalance;
    }

    public function __toArray()
    {
        return [
            "destination" => [
                "id" => strval($this->destinationId),
                "balance" => $this->newBalance
            ]
        ];
    }
}
