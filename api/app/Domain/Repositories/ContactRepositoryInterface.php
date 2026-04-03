<?php

namespace App\Domain\Repositories;

interface ContactRepositoryInterface
{
    public function findAll(?string $search = null): array;

    public function findById(int $id): ?array;

    public function findByEmail(string $email): ?array;

    public function create(array $data): array;

    public function update(int $id, array $data): array;

    public function delete(int $id): void;
}
