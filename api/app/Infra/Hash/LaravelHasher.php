<?php

namespace App\Infra\Hash;

use App\Application\Contracts\HasherInterface;
use Illuminate\Support\Facades\Hash;

class LaravelHasher implements HasherInterface
{
    public function make(string $value): string
    {
        return Hash::make($value);
    }
}
