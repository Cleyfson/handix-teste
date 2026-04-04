<?php

namespace App\Infra\Repositories;

use App\Domain\Entities\User as UserEntity;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Models\User;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function findByEmail(string $email): ?UserEntity
    {
        $model = User::where('email', $email)->first();

        return $model ? $this->toEntity($model) : null;
    }

    public function create(string $name, string $email, string $hashedPassword): UserEntity
    {
        $model = User::create([
            'name'     => $name,
            'email'    => $email,
            'password' => $hashedPassword,
        ]);

        return $this->toEntity($model);
    }

    private function toEntity(User $model): UserEntity
    {
        return (new UserEntity())
            ->setId($model->id)
            ->setName($model->name)
            ->setEmail($model->email)
            ->setCreatedAt($model->created_at?->toIso8601String())
            ->setUpdatedAt($model->updated_at?->toIso8601String());
    }
}
