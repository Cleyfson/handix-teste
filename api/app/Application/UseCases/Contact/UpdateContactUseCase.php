<?php

namespace App\Application\UseCases\Contact;

use App\Domain\Repositories\ContactRepositoryInterface;
use Illuminate\Validation\ValidationException;

class UpdateContactUseCase
{
    public function __construct(
        private readonly ContactRepositoryInterface $repository
    ) {}

    public function execute(int $id, array $data): array
    {
        $existing = $this->repository->findByEmail($data['email'] ?? '');

        if ($existing && $existing['id'] !== $id) {
            throw ValidationException::withMessages([
                'email' => ['The email has already been taken.'],
            ]);
        }

        return $this->repository->update($id, $data);
    }
}
