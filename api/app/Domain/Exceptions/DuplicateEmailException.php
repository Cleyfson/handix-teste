<?php

namespace App\Domain\Exceptions;

use RuntimeException;

class DuplicateEmailException extends RuntimeException
{
    private string $field;

    public function __construct(string $message = 'The email has already been taken.', string $field = 'email')
    {
        parent::__construct($message);
        $this->field = $field;
    }

    public function getField(): string
    {
        return $this->field;
    }
}
