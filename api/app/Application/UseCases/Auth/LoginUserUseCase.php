<?php

namespace App\Application\UseCases\Auth;

use Illuminate\Validation\ValidationException;

class LoginUserUseCase
{
    public function execute(string $email, string $password): array
    {
        $token = auth('api')->attempt(['email' => $email, 'password' => $password]);

        if (!$token) {
            throw ValidationException::withMessages([
                'email' => ['Credenciais inválidas.'],
            ]);
        }

        return [
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth('api')->factory()->getTTL() * 60,
            'user'         => auth('api')->user(),
        ];
    }
}
