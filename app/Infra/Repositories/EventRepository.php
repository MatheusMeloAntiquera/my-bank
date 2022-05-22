<?php

namespace App\Infra\Repositories;

use DateTime;
use App\Domain\Entity\Event;
use Illuminate\Support\Facades\DB;
use App\Domain\Repositories\EventRepositoryInterface;

class EventRepository implements EventRepositoryInterface
{
    private $table = 'events';
    public function findById(int $id): Event
    {
        $result = DB::table($this->table)
            ->where('id', $id)->first();

        if (empty($result)) {
            return null;
        }

        return $this->createEventEntity($result);
    }

    public function create(Event $event): Event
    {
        $createdAt = new DateTime("now");
        $id = DB::table($this->table)->insertGetId(
            [
                'type' => $event->type,
                'destination' => $event->destination,
                'origin' => $event->origin,
                'amount' => $event->amount,
                'created_at' => $createdAt,
                'updated_at' => null,
            ]
        );

        $event->id = $id;
        $event->created_at = $createdAt;

        return $event;
    }

    private function createEventEntity(object $result)
    {
        return new Event(
            id: $result->id,
            type: $result->type,
            destination: $result->destination,
            origin: $result->origin,
            amount: $result->amount,
            created_at: $result->created_at,
            updated_at: $result->updated_at,
        );
    }
}
