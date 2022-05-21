<?php

namespace Tests\Unit;

use DateTime;
use Tests\TestCase;
use Mockery\MockInterface;
use App\Domain\Entity\Account;
use Illuminate\Support\Facades\App;
use App\UseCase\Account\AccountService;
use App\Application\Repositories\AccountRepositoryInterface;

class AccountServiceTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function shouldNotFoundAnAccount()
    {

        $this->partialMock(
            AccountRepositoryInterface::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('findById')->once()->andReturn(null);
            }
        );

        $this->accountService = App::make(AccountService::class);
        $account = $this->accountService->findByAccountId(1235);
        $this->assertNull($account);
    }

    /**
     * @test
     * @return void
     */
    public function shouldReturnAnAccount()
    {

        $this->partialMock(
            AccountRepositoryInterface::class,
            function (MockInterface $mock) {
                $account = new Account();
                $account->id = 1;
                $account->name = "Jon Snow";
                $account->email = "jon_snow@stark.com";
                $account->balance = 100;
                $account->active = true;
                $account->created_at = new DateTime("2022-05-21 12:00:00");
                $account->updated_at = null;
                $mock->shouldReceive('findById')->once()->andReturn($account);
            }
        );

        $this->accountService = App::make(AccountService::class);
        $account = $this->accountService->findByAccountId(1);
        $this->assertInstanceOf(Account::class, $account);
        $this->assertEquals(1, $account->id);
    }
}
