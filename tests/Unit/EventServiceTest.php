<?php

namespace Tests\Unit;

use DateTime;
use Tests\TestCase;
use Mockery\MockInterface;
use App\Domain\Entity\User;
use App\Domain\Entity\Account;
use Illuminate\Support\Facades\App;
use App\Application\Repositories\AccountRepositoryInterface;

class EventServiceTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function shouldCreateAccountWithInitialBalance()
    {
        $amount = 150.00;
        $user = new User();
        $user->id = 10;
        $user->name = "Jon Snow";
        $user->email = "jon_snow@stark.com";
        $user->account_id = null;
        $user->active = true;
        $user->created_at = new DateTime("2022-05-21 12:00:00");
        $user->updated_at = null;

        $this->partialMock(
            UserRepositoryInterface::class,
            function (MockInterface $mock) use ($user) {
                $mock->shouldReceive('findById')->once()->andReturn($user);
            }
        );

        $this->partialMock(
            AccountRepositoryInterface::class,
            function (MockInterface $mock) use ($amount) {
                $account = new Account();
                $account->id = 1;
                $account->active = true;
                $account->balance = $amount;
                $account->created_at = new DateTime("2022-05-21 12:00:00");
                $account->updated_at = null;
                $mock->shouldReceive('create')->once()->andReturn($account);
            }
        );

        $this->partialMock(
            UserRepositoryInterface::class,
            function (MockInterface $mock) use ($user) {
                $user->account_id = 1;
                $mock->shouldReceive('addAccount')->once()->andReturn($user);
            }
        );

        $this->partialMock(
            EventRepositoryInterface::class,
            function (MockInterface $mock) {
                $event = new Event();
                $event->id = 1;
                $event->type = Event::TYPE_DEPOSIT;
                $event->origin = null;
                $event->destination = 10;
                $event->amount = 150.00;
                $event->created_at = new DateTime("2022-05-21 18:00:00");
                $event->updated_at = null;
                $mock->shouldReceive('create')->once()->andReturn($event);
            }
        );

        /**
         * @var EventService $eventService
         */
        $this->eventService = App::make(EventService::class);
        $eventDepositDto = $this->eventService->deposit($user->id, 150.00);
        $this->assertInstanceOf(EventDepositDto::class, $eventDepositDto);
        $this->assertEquals(10, $eventDepositDto->destination->id);
        $this->assertEquals(150, $eventDepositDto->destination->balance);
    }
}
