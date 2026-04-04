<?php

namespace Tests\Unit\Application\Contact;

use App\Application\UseCases\Contact\ListContactsUseCase;
use App\Domain\Entities\Contact;
use App\Domain\Repositories\ContactRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ListContactsUseCaseTest extends TestCase
{
    private ContactRepositoryInterface&MockObject $repository;
    private ListContactsUseCase $useCase;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(ContactRepositoryInterface::class);
        $this->useCase = new ListContactsUseCase($this->repository);
    }

    public function test_returns_all_contacts_without_search(): void
    {
        $contacts = [
            (new Contact())->setId(1)->setName('Ana')->setEmail('ana@example.com'),
            (new Contact())->setId(2)->setName('Bruno')->setEmail('bruno@example.com'),
        ];

        $this->repository->expects($this->once())
            ->method('findAll')
            ->with(null)
            ->willReturn($contacts);

        $result = $this->useCase->execute();

        $this->assertCount(2, $result);
    }

    public function test_passes_search_term_to_repository(): void
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->with('ana')
            ->willReturn([]);

        $result = $this->useCase->execute('ana');

        $this->assertSame([], $result);
    }
}
