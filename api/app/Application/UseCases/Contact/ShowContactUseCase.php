<?php

namespace App\Application\UseCases\Contact;

use App\Domain\Entities\Contact;
use App\Domain\Repositories\ContactRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ShowContactUseCase
{
    public function __construct(
        private readonly ContactRepositoryInterface $repository
    ) {}

    public function execute(int $id): Contact
    {
        $contact = $this->repository->findById($id);

        if (!$contact) {
            throw (new ModelNotFoundException())->setModel(Contact::class, $id);
        }

        return $contact;
    }
}
