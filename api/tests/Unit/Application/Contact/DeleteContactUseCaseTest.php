<?php

namespace Tests\Unit\Application\Contact;

use App\Application\UseCases\Contact\DeleteContactUseCase;
use App\Domain\Repositories\ContactRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DeleteContactUseCaseTest extends TestCase
{
    private ContactRepositoryInterface&MockObject $repository;
    private DeleteContactUseCase $useCase;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(ContactRepositoryInterface::class);
        $this->useCase = new DeleteContactUseCase($this->repository);
    }

    public function test_calls_repository_delete_with_correct_id(): void
    {
        $this->repository->expects($this->once())
            ->method('delete')
            ->with(5);

        $this->useCase->execute(5);
    }
}
