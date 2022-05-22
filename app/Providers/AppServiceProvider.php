<?php

namespace App\Providers;

use App\UseCase\Event\EventService;
use App\UseCase\Account\AccountService;
use Illuminate\Support\ServiceProvider;
use App\Domain\Repositories\EventRepository;
use App\UseCase\Event\EventServiceInterface;
use App\Infra\Repositories\AccountRepository;
use App\UseCase\Account\AccountServiceInterface;
use App\Domain\Repositories\EventRepositoryInterface;
use App\Domain\Repositories\AccountRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AccountServiceInterface::class, AccountService::class);
        $this->app->bind(AccountRepositoryInterface::class, AccountRepository::class);
        $this->app->bind(EventServiceInterface::class, EventService::class);
        $this->app->bind(EventRepositoryInterface::class, EventRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
