<?php

namespace Tests\Unit\Application\Contact;

use App\Application\UseCases\Contact\UpdateContactUseCase;
use App\Domain\Entities\Contact;
use App\Domain\Exceptions\DuplicateEmailException;
use App\Domain\Repositories\ContactRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UpdateContactUseCaseTest extends TestCase
{
    private ContactRepositoryInterface&MockObject $repository;
    private UpdateContactUseCase $useCase;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(ContactRepositoryInterface::class);
        $this->useCase = new UpdateContactUseCase($this->repository);
    }

    public function test_updates_contact_with_valid_data(): void
    {
        $data = ['name' => 'Maria', 'email' => 'maria@example.com'];

        $this->repository->method('findByEmail')->willReturn(null);

        $updated = (new Contact())->setId(1)->setName('Maria')->setEmail('maria@example.com');
        $this->repository->method('update')->willReturn($updated);

        $result = $this->useCase->execute(1, $data);

        $this->assertInstanceOf(Contact::class, $result);
        $this->assertSame('Maria', $result->getName());
    }

    public function test_allows_update_keeping_own_email(): void
    {
        $data = ['name' => 'Maria', 'email' => 'maria@example.com'];

        // findByEmail retorna o próprio contato (mesmo ID)
        $self = (new Contact())->setId(1)->setName('Maria')->setEmail('maria@example.com');
        $this->repository->method('findByEmail')->willReturn($self);

        $this->repository->method('update')->willReturn($self);

        $result = $this->useCase->execute(1, $data);

        $this->assertSame(1, $result->getId());
    }

    public function test_throws_validation_exception_when_email_belongs_to_another_contact(): void
    {
        $this->expectException(DuplicateEmailException::class);

        $other = (new Contact())->setId(2)->setName('Outro')->setEmail('joao@example.com');
        $this->repository->method('findByEmail')->willReturn($other);

        $this->useCase->execute(1, ['name' => 'João', 'email' => 'joao@example.com']);
    }
}
