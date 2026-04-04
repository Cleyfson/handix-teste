<?php

namespace App\Application\UseCases\Auth;

use App\Domain\Entities\User;
use App\Domain\Exceptions\DuplicateEmailException;
use App\Domain\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class RegisterUserUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $repository
    ) {}

    public function execute(string $name, string $email, string $password): array
    {
        if ($this->repository->findByEmail($email)) {
            throw new DuplicateEmailException('O e-mail informado já está em uso.');
        }

        $user = $this->repository->create($name, $email, Hash::make($password));

        $token = auth('api')->login($this->findEloquentUser($email));

        return $this->tokenPayload($token, $user);
    }

    private function findEloquentUser(string $email): \App\Models\User
    {
        return \App\Models\User::where('email', $email)->firstOrFail();
    }

    private function tokenPayload(string $token, User $user): array
    {
        return [
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth('api')->factory()->getTTL() * 60,
            'user'         => $user,
        ];
    }
}
