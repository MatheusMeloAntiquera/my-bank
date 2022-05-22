<?php

namespace Tests\Unit;

use DateTime;
use Tests\TestCase;
use Mockery\MockInterface;
use App\Domain\Entity\User;
use App\Domain\Entity\Event;
use App\Domain\Entity\Account;
use App\UseCase\Event\EventService;
use Illuminate\Support\Facades\App;
use App\UseCase\Event\EventDepositDto;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Repositories\EventRepositoryInterface;
use App\Domain\Repositories\AccountRepositoryInterface;

class EventServiceTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function shouldCreateAccountWithInitialBalance()
    {
        $amount = 150.00;
        $user = new User(
            id: 10,
            name: 'John Snow',
            email: "jon_snow@stark.com",
            created_at: new DateTime("2022-05-21 12:00:00")
        );

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
                $mock->shouldReceive('findOrcreate')->once()->andReturn($account);
                $mock->shouldReceive('updateBalance')->once()->andReturn($account);
            }
        );

        $this->partialMock(
            EventRepositoryInterface::class,
            function (MockInterface $mock) use ($user) {
                $event = new Event(
                    id: 1,
                    type: Event::TYPE_DEPOSIT,
                    amount: 150.00,
                    destination: $user->id,
                    created_at: new DateTime("now")
                );
                $mock->shouldReceive('create')->once()->andReturn($event);
            }
        );

        /**
         * @var EventService $eventService
         */
        $this->eventService = App::make(EventService::class);
        $eventDepositDto = $this->eventService->deposit($user->id, 150.00);
        $this->assertInstanceOf(EventDepositDto::class, $eventDepositDto);
        $eventDepositDtoArray = $eventDepositDto->__toArray();
        $this->assertEquals(10, $eventDepositDtoArray['destination']['id']);
        $this->assertEquals(150, $eventDepositDtoArray['destination']['balance']);
    }
}
