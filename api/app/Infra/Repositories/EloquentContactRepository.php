<?php

namespace App\Infra\Repositories;

use App\Domain\Entities\Contact as ContactEntity;
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

        return $query->orderBy('name')->get()->map(fn($m) => $this->toEntity($m))->all();
    }

    public function findById(int $id): ?ContactEntity
    {
        $contact = Contact::find($id);

        return $contact ? $this->toEntity($contact) : null;
    }

    public function findByEmail(string $email): ?ContactEntity
    {
        $contact = Contact::where('email', $email)->first();

        return $contact ? $this->toEntity($contact) : null;
    }

    public function create(ContactEntity $contact): ContactEntity
    {
        $model = Contact::create([
            'name'  => $contact->getName(),
            'email' => $contact->getEmail(),
            'phone' => $contact->getPhone(),
            'notes' => $contact->getNotes(),
        ]);

        return $this->toEntity($model);
    }

    public function update(int $id, ContactEntity $contact): ContactEntity
    {
        $model = Contact::findOrFail($id);

        $model->update([
            'name'  => $contact->getName(),
            'email' => $contact->getEmail(),
            'phone' => $contact->getPhone(),
            'notes' => $contact->getNotes(),
        ]);

        return $this->toEntity($model->fresh());
    }

    public function delete(int $id): void
    {
        Contact::findOrFail($id)->delete();
    }

    private function toEntity(Contact $model): ContactEntity
    {
        return (new ContactEntity())
            ->setId($model->id)
            ->setName($model->name)
            ->setEmail($model->email)
            ->setPhone($model->phone)
            ->setNotes($model->notes)
            ->setCreatedAt($model->created_at?->toIso8601String())
            ->setUpdatedAt($model->updated_at?->toIso8601String());
    }
}
