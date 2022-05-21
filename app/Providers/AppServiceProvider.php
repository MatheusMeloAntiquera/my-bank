<?php

namespace App\Providers;

use App\UseCase\Account\AccountService;
use Illuminate\Support\ServiceProvider;
use App\Infra\Repositories\AccountRepository;
use App\UseCase\Account\AccountServiceInterface;
use App\Application\Repositories\AccountRepositoryInterface;

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
