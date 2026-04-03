<?php

namespace App\Application\UseCases\Contact;

use App\Domain\Repositories\ContactRepositoryInterface;

class ListContactsUseCase
{
    public function __construct(
        private readonly ContactRepositoryInterface $repository
    ) {}

    public function execute(?string $search = null): array
    {
        return $this->repository->findAll($search);
    }
}
