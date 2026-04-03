<?php

namespace App\Application\UseCases\Contact;

use App\Domain\Repositories\ContactRepositoryInterface;
use Illuminate\Validation\ValidationException;

class CreateContactUseCase
{
    public function __construct(
        private readonly ContactRepositoryInterface $repository
    ) {}

    public function execute(array $data): array
    {
        if ($this->repository->findByEmail($data['email'])) {
            throw ValidationException::withMessages([
                'email' => ['The email has already been taken.'],
            ]);
        }

        return $this->repository->create($data);
    }
}
