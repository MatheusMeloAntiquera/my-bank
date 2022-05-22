<?php

namespace App\Domain\Entity;

use DateTime;

class Event
{
    const TYPE_DEPOSIT = 1;
    const TYPE_WITHDRAW = 2;
    const TYPE_TRANSFER = 3;

    public ?int $id;
    public int $type;
    public float $amount;
    public ?int $destination;
    public ?int $origin;
    public ?Datetime $created_at;
    public ?DateTime $updated_at;

    public function __construct(
        int $type,
        float $amount,
        ?int $id = null,
        ?int $destination = null,
        ?int $origin = null,
        ?Datetime $created_at = null,
        ?DateTime $updated_at = null,
    ) {
        $this->id = $id;
        $this->type = $type;
        $this->amount = $amount;
        $this->destination = $destination;
        $this->origin = $origin;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
}
