<?php

namespace App\Providers;

use App\Domain\Repositories\ContactRepositoryInterface;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Infra\Repositories\EloquentContactRepository;
use App\Infra\Repositories\EloquentUserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ContactRepositoryInterface::class, EloquentContactRepository::class);
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
