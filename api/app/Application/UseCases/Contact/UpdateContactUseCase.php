<?php

namespace App\Application\UseCases\Contact;

use App\Domain\Entities\Contact;
use App\Domain\Repositories\ContactRepositoryInterface;
use Illuminate\Validation\ValidationException;

class UpdateContactUseCase
{
    public function __construct(
        private readonly ContactRepositoryInterface $repository
    ) {}

    public function execute(int $id, array $data): Contact
    {
        $existing = $this->repository->findByEmail($data['email'] ?? '');

        if ($existing && $existing->getId() !== $id) {
            throw ValidationException::withMessages([
                'email' => ['The email has already been taken.'],
            ]);
        }

        return $this->repository->update($id, Contact::fromArray($data));
    }
}
