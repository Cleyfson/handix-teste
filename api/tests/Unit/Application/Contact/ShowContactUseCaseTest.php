<?php

namespace Tests\Unit\Application\Contact;

use App\Application\UseCases\Contact\ShowContactUseCase;
use App\Domain\Entities\Contact;
use App\Domain\Repositories\ContactRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ShowContactUseCaseTest extends TestCase
{
    private ContactRepositoryInterface&MockObject $repository;
    private ShowContactUseCase $useCase;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(ContactRepositoryInterface::class);
        $this->useCase = new ShowContactUseCase($this->repository);
    }

    public function test_returns_contact_when_found(): void
    {
        $contact = (new Contact())->setId(1)->setName('Ana')->setEmail('ana@example.com');

        $this->repository->method('findById')->with(1)->willReturn($contact);

        $result = $this->useCase->execute(1);

        $this->assertInstanceOf(Contact::class, $result);
        $this->assertSame(1, $result->getId());
    }

    public function test_throws_model_not_found_when_contact_does_not_exist(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->repository->method('findById')->with(99)->willReturn(null);

        $this->useCase->execute(99);
    }
}
