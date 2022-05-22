<?php

namespace Tests\Unit;

use DateTime;
use Tests\TestCase;
use Mockery\MockInterface;
use App\Domain\Entity\Event;
use App\Domain\Entity\Account;
use App\UseCase\Event\EventService;
use Illuminate\Support\Facades\App;
use App\UseCase\Event\EventDepositDto;
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
        $accountId = 100;

        $this->partialMock(
            AccountRepositoryInterface::class,
            function (MockInterface $mock) use ($amount, $accountId) {
                $account = new Account();
                $account->id = $accountId;
                $account->active = true;
                $account->balance = 0.0;
                $account->created_at = new DateTime("2022-05-21 12:00:00");
                $account->updated_at = null;
                $mock->shouldReceive('findOrcreate')->once()->andReturn($account);
                $mock->shouldReceive('updateBalance')->once()->andReturn($account);
            }
        );

        $this->partialMock(
            EventRepositoryInterface::class,
            function (MockInterface $mock) {
                $event = new Event(
                    id: 1,
                    type: Event::TYPE_DEPOSIT,
                    amount: 150.00,
                    destination: 100,
                    created_at: new DateTime("now")
                );
                $mock->shouldReceive('create')->once()->andReturn($event);
            }
        );

        /**
         * @var EventService $eventService
         */
        $this->eventService = App::make(EventService::class);
        $eventDepositDto = $this->eventService->deposit($accountId, 150.00);
        $this->assertInstanceOf(EventDepositDto::class, $eventDepositDto);
        $eventDepositDtoArray = $eventDepositDto->__toArray();
        $this->assertEquals($accountId, $eventDepositDtoArray['destination']['id']);
        $this->assertEquals(150, $eventDepositDtoArray['destination']['balance']);
    }
}
