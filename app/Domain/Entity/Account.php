<?php

namespace App\Domain\Entity;

use DateTime;

class Account
{
    public int $id;
    public string $name;
    public string $email;
    public float $balance;
    public bool $active;
    public Datetime $created_at;
    public ?DateTime $updated_at;
}
