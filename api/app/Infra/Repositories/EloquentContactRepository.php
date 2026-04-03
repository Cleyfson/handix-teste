<?php

namespace App\Infra\Repositories;

use App\Domain\Repositories\ContactRepositoryInterface;
use App\Models\Contact;

class EloquentContactRepository implements ContactRepositoryInterface
{
    public function findAll(?string $search = null): array
    {
        $query = Contact::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('name')->get()->toArray();
    }

    public function findById(int $id): ?array
    {
        $contact = Contact::find($id);

        return $contact?->toArray();
    }

    public function findByEmail(string $email): ?array
    {
        $contact = Contact::where('email', $email)->first();

        return $contact?->toArray();
    }

    public function create(array $data): array
    {
        return Contact::create($data)->toArray();
    }

    public function update(int $id, array $data): array
    {
        $contact = Contact::findOrFail($id);
        $contact->update($data);

        return $contact->fresh()->toArray();
    }

    public function delete(int $id): void
    {
        Contact::findOrFail($id)->delete();
    }
}
