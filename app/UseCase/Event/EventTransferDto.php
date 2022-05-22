<?php

namespace App\UseCase\Event;

use App\Domain\Entity\Account;

class EventTransferDto
{
    private Account $origin;
    private Account $destination;

    public function __construct(Account $origin, Account $destination)
    {
        $this->origin = $origin;
        $this->destination = $destination;
    }

    public function __toArray()
    {
        return [
            "origin" => [
                "id" => strval($this->origin->id),
                "balance" => $this->origin->balance
            ],
            "destination" => [
                "id" => strval($this->destination->id),
                "balance" => $this->destination->balance
            ]
        ];
    }
}
