<?php

namespace App\Application\UseCases\Contact;

use App\Domain\Entities\Contact;
use App\Domain\Exceptions\DuplicateEmailException;
use App\Domain\Repositories\ContactRepositoryInterface;

class CreateContactUseCase
{
    public function __construct(
        private readonly ContactRepositoryInterface $repository
    ) {}

    public function execute(array $data): Contact
    {
        if ($this->repository->findByEmail($data['email'])) {
            throw new DuplicateEmailException();
        }

        return $this->repository->create(Contact::fromArray($data));
    }
}
