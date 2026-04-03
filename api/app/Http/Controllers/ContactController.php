<?php

namespace App\Http\Controllers;

use App\Application\UseCases\Contact\CreateContactUseCase;
use App\Application\UseCases\Contact\DeleteContactUseCase;
use App\Application\UseCases\Contact\ListContactsUseCase;
use App\Application\UseCases\Contact\UpdateContactUseCase;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ContactController extends Controller
{
    public function __construct(
        private readonly ListContactsUseCase $listContacts,
        private readonly CreateContactUseCase $createContact,
        private readonly UpdateContactUseCase $updateContact,
        private readonly DeleteContactUseCase $deleteContact,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $contacts = $this->listContacts->execute($request->query('search'));

        return response()->json($contacts);
    }

    public function store(StoreContactRequest $request): JsonResponse
    {
        $contact = $this->createContact->execute($request->validated());

        return response()->json($contact, 201);
    }

    public function show(int $id): JsonResponse
    {
        // Direct repository lookup not exposed; use list for simplicity.
        // Delegated to the model for a thin show action.
        $contact = \App\Models\Contact::findOrFail($id);

        return response()->json($contact);
    }

    public function update(UpdateContactRequest $request, int $contact): JsonResponse
    {
        $updated = $this->updateContact->execute($contact, $request->validated());

        return response()->json($updated);
    }

    public function destroy(int $contact): JsonResponse
    {
        $this->deleteContact->execute($contact);

        return response()->json(null, 204);
    }
}
