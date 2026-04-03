<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Contact;

interface ContactRepositoryInterface
{
    /** @return Contact[] */
    public function findAll(?string $search = null): array;

    public function findById(int $id): ?Contact;

    public function findByEmail(string $email): ?Contact;

    public function create(Contact $contact): Contact;

    public function update(int $id, Contact $contact): Contact;

    public function delete(int $id): void;
}
