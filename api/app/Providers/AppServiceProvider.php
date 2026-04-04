<?php

namespace App\Providers;

use App\Application\Contracts\HasherInterface;
use App\Application\Contracts\JwtAuthInterface;
use App\Domain\Repositories\ContactRepositoryInterface;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Infra\Auth\LaravelJwtAuth;
use App\Infra\Hash\LaravelHasher;
use App\Infra\Repositories\EloquentContactRepository;
use App\Infra\Repositories\EloquentUserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ContactRepositoryInterface::class, EloquentContactRepository::class);
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
        $this->app->bind(HasherInterface::class, LaravelHasher::class);
        $this->app->bind(JwtAuthInterface::class, LaravelJwtAuth::class);
    }

    public function boot(): void
    {
        //
    }
}
