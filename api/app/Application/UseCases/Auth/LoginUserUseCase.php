<?php

namespace App\Application\UseCases\Auth;

use App\Application\Contracts\JwtAuthInterface;
use App\Domain\Exceptions\InvalidCredentialsException;

class LoginUserUseCase
{
    public function __construct(
        private readonly JwtAuthInterface $auth,
    ) {}

    public function execute(string $email, string $password): array
    {
        $token = $this->auth->attempt($email, $password);

        if (!$token) {
            throw new InvalidCredentialsException('Credenciais inválidas.');
        }

        return [
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => $this->auth->getTTL(),
            'user'         => $this->auth->getCurrentUser(),
        ];
    }
}
