<?php

namespace App\Application\Contracts;

interface JwtAuthInterface
{
    public function loginByEmail(string $email): string;

    public function attempt(string $email, string $password): string|false;

    public function getCurrentUser(): mixed;

    public function getTTL(): int;
}
