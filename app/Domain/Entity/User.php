<?php

namespace App\Domain\Entity;

use DateTime;

class User
{
    public int $id;
    public string $name;
    public string $email;
    public bool $active;
    public ?int $account_id;
    public Datetime $created_at;
    public ?DateTime $updated_at;
}
