<?php

namespace App\Domain\Entity;

use DateTime;

class User
{
    public ?int $id;
    public string $name;
    public string $email;
    public bool $active;
    public ?int $account_id;
    public Datetime $created_at;
    public ?DateTime $updated_at;

    public function __construct(
        string $name,
        string $email,
        bool $active = true,
        ?int $id = null,
        ?int $account_id = null,
        ?Datetime $created_at = null,
        ?DateTime $updated_at = null,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->active = $active;
        $this->account_id = $account_id;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
}
