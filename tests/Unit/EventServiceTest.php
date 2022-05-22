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
use App\UseCase\Event\EventWithdrawDto;
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
        $amount = 150;
        $accountId = 100;

        $this->partialMock(
            AccountRepositoryInterface::class,
            function (MockInterface $mock) use ($accountId, $amount) {
                $account = new Account();
                $account->id = $accountId;
                $account->active = true;
                $account->balance = 0.0;
                $account->created_at = new DateTime("2022-05-21 12:00:00");
                $account->updated_at = null;
                $mock->shouldReceive('findOrcreate')->once()->andReturn($account);

                $accountUpdated = clone $account;
                $accountUpdated->balance += $amount;
                $mock->shouldReceive('updateBalance')->once()->andReturn($accountUpdated);
            }
        );

        $this->partialMock(
            EventRepositoryInterface::class,
            function (MockInterface $mock) use ($amount, $accountId) {
                $event = new Event(
                    id: 1,
                    type: Event::TYPE_DEPOSIT,
                    amount: $amount,
                    destination: $accountId,
                    created_at: new DateTime("now")
                );
                $mock->shouldReceive('create')->once()->andReturn($event);
            }
        );

        /**
         * @var EventService $eventService
         */
        $this->eventService = App::make(EventService::class);
        $eventDepositDto = $this->eventService->deposit($accountId, $amount);
        $this->assertInstanceOf(EventDepositDto::class, $eventDepositDto);
        $eventDepositDtoArray = $eventDepositDto->__toArray();
        $this->assertEquals($accountId, $eventDepositDtoArray['destination']['id']);
        $this->assertEquals($amount, $eventDepositDtoArray['destination']['balance']);
    }

    /**
     * @test
     * @return void
     */
    public function shouldDepositIntoExistingAccountSuccessfully()
    {
        $amount = 100;
        $accountId = 1;
        $balance = 100;

        $this->partialMock(
            AccountRepositoryInterface::class,
            function (MockInterface $mock) use ($accountId, $balance, $amount) {
                $account = new Account();
                $account->id = $accountId;
                $account->active = true;
                $account->balance = $balance;
                $account->created_at = new DateTime("2022-05-21 12:00:00");
                $account->updated_at = null;
                $mock->shouldReceive('findOrcreate')->once()->andReturn($account);

                $accountUpdated = clone $account;
                $accountUpdated->balance = $balance + $amount;
                $mock->shouldReceive('updateBalance')->once()->andReturn($accountUpdated);
            }
        );

        $this->partialMock(
            EventRepositoryInterface::class,
            function (MockInterface $mock) use ($amount, $accountId) {
                $event = new Event(
                    id: 1,
                    type: Event::TYPE_DEPOSIT,
                    amount: $amount,
                    destination: $accountId,
                    created_at: new DateTime("now")
                );
                $mock->shouldReceive('create')->once()->andReturn($event);
            }
        );

        /**
         * @var EventService $eventService
         */
        $this->eventService = App::make(EventService::class);
        $eventDepositDto = $this->eventService->deposit($accountId, $amount);
        $this->assertInstanceOf(EventDepositDto::class, $eventDepositDto);
        $eventDepositDtoArray = $eventDepositDto->__toArray();
        $this->assertEquals($accountId, $eventDepositDtoArray['destination']['id']);
        $this->assertEquals($balance + $amount, $eventDepositDtoArray['destination']['balance']);
    }

    /**
     * @test
     * @return void
     */
    public function shouldNotAllowToWithdrawFromNonExistingAccount()
    {
        $this->partialMock(
            AccountRepositoryInterface::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('findById')->once()->andReturn(null);
            }
        );

        /**
         * @var EventService $eventService
         */
        $this->eventService = App::make(EventService::class);
        $result = $this->eventService->withdraw(50, 50);
        $this->assertEquals(0, $result);
    }

    /**
     * @test
     * @return void
     */
    public function shouldWithdrawFromExistingAccount()
    {
        $amount = 50;
        $balance = 100;
        $accountId = 100;

        $this->partialMock(
            AccountRepositoryInterface::class,
            function (MockInterface $mock) use ($accountId, $balance, $amount) {
                $account = new Account();
                $account->id = $accountId;
                $account->active = true;
                $account->balance = $balance;
                $account->created_at = new DateTime("2022-05-21 12:00:00");
                $account->updated_at = null;
                $mock->shouldReceive('findById')->once()->andReturn($account);

                $accountUpdated = clone $account;
                $accountUpdated->balance = $balance - $amount;
                $mock->shouldReceive('updateBalance')->once()->andReturn($accountUpdated);
            }
        );

        $this->partialMock(
            EventRepositoryInterface::class,
            function (MockInterface $mock) use ($amount, $accountId) {
                $event = new Event(
                    id: 1,
                    type: Event::TYPE_WITHDRAW,
                    amount: $amount,
                    origin: $accountId,
                    created_at: new DateTime("now")
                );
                $mock->shouldReceive('create')->once()->andReturn($event);
            }
        );

        /**
         * @var EventService $eventService
         */
        $this->eventService = App::make(EventService::class);
        $eventDepositDto = $this->eventService->withdraw($accountId, $amount);
        $this->assertInstanceOf(EventWithdrawDto::class, $eventDepositDto);
        $eventDepositDtoArray = $eventDepositDto->__toArray();
        $this->assertEquals($accountId, $eventDepositDtoArray['origin']['id']);
        $this->assertEquals($balance - $amount, $eventDepositDtoArray['origin']['balance']);
    }
}
