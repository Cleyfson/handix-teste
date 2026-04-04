<?php

namespace Tests\Unit\Application\Contact;

use App\Application\UseCases\Contact\CreateContactUseCase;
use App\Domain\Entities\Contact;
use App\Domain\Exceptions\DuplicateEmailException;
use App\Domain\Repositories\ContactRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateContactUseCaseTest extends TestCase
{
    private ContactRepositoryInterface&MockObject $repository;
    private CreateContactUseCase $useCase;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(ContactRepositoryInterface::class);
        $this->useCase = new CreateContactUseCase($this->repository);
    }

    public function test_creates_contact_with_valid_data(): void
    {
        $data = ['name' => 'João', 'email' => 'joao@example.com', 'phone' => null, 'notes' => null];

        $this->repository->method('findByEmail')->willReturn(null);

        $expected = (new Contact())->setId(1)->setName('João')->setEmail('joao@example.com');
        $this->repository->method('create')->willReturn($expected);

        $result = $this->useCase->execute($data);

        $this->assertInstanceOf(Contact::class, $result);
        $this->assertSame('João', $result->getName());
    }

    public function test_throws_validation_exception_when_email_already_exists(): void
    {
        $this->expectException(DuplicateEmailException::class);

        $existing = (new Contact())->setId(1)->setName('Outro')->setEmail('joao@example.com');
        $this->repository->method('findByEmail')->willReturn($existing);

        $this->useCase->execute(['name' => 'João', 'email' => 'joao@example.com']);
    }
}
