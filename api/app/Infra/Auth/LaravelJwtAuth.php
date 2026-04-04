<?php

namespace App\Infra\Auth;

use App\Application\Contracts\JwtAuthInterface;

class LaravelJwtAuth implements JwtAuthInterface
{
    public function loginByEmail(string $email): string
    {
        $user = \App\Models\User::where('email', $email)->firstOrFail();
        return auth('api')->login($user);
    }

    public function attempt(string $email, string $password): string|false
    {
        return auth('api')->attempt(['email' => $email, 'password' => $password]);
    }

    public function getCurrentUser(): mixed
    {
        return auth('api')->user();
    }

    public function getTTL(): int
    {
        return auth('api')->factory()->getTTL() * 60;
    }
}
