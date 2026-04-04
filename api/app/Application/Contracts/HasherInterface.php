<?php

namespace App\Application\Contracts;

interface HasherInterface
{
    public function make(string $value): string;
}
