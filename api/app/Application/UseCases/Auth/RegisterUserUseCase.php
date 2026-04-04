<?php

namespace App\Application\UseCases\Auth;

use App\Application\Contracts\HasherInterface;
use App\Application\Contracts\JwtAuthInterface;
use App\Domain\Entities\User;
use App\Domain\Exceptions\DuplicateEmailException;
use App\Domain\Repositories\UserRepositoryInterface;

class RegisterUserUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $repository,
        private readonly HasherInterface $hasher,
        private readonly JwtAuthInterface $auth,
    ) {}

    public function execute(string $name, string $email, string $password): array
    {
        if ($this->repository->findByEmail($email)) {
            throw new DuplicateEmailException('O e-mail informado já está em uso.');
        }

        $user = $this->repository->create($name, $email, $this->hasher->make($password));

        $token = $this->auth->loginByEmail($email);

        return [
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => $this->auth->getTTL(),
            'user'         => $user,
        ];
    }
}
