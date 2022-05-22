<?php

namespace App\Domain\Entity;

use DateTime;

class Account
{
    public ?int $id;
    public float $balance;
    public bool $active;
    public ?Datetime $created_at;
    public ?DateTime $updated_at;

    public function __construct(
        float $balance = 0.0,
        bool $active = true,
        ?int $id = null,
        ?Datetime $created_at = null,
        ?DateTime $updated_at = null,
    ) {
        $this->id = $id;
        $this->active = $active;
        $this->balance = $balance;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
}
