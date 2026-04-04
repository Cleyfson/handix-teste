<?php

namespace Tests\Unit\Application\Auth;

use App\Application\Contracts\HasherInterface;
use App\Application\Contracts\JwtAuthInterface;
use App\Application\UseCases\Auth\RegisterUserUseCase;
use App\Domain\Entities\User;
use App\Domain\Exceptions\DuplicateEmailException;
use App\Domain\Repositories\UserRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RegisterUserUseCaseTest extends TestCase
{
    private UserRepositoryInterface&MockObject $repository;
    private HasherInterface&MockObject $hasher;
    private JwtAuthInterface&MockObject $auth;
    private RegisterUserUseCase $useCase;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(UserRepositoryInterface::class);
        $this->hasher     = $this->createMock(HasherInterface::class);
        $this->auth       = $this->createMock(JwtAuthInterface::class);
        $this->useCase    = new RegisterUserUseCase($this->repository, $this->hasher, $this->auth);
    }

    public function test_returns_token_payload_on_success(): void
    {
        $this->repository->method('findByEmail')->willReturn(null);
        $this->hasher->method('make')->willReturn('hashed_password');

        $user = (new User())->setName('João')->setEmail('joao@example.com');
        $this->repository->method('create')->willReturn($user);

        $this->auth->method('loginByEmail')->willReturn('jwt-token');
        $this->auth->method('getTTL')->willReturn(3600);

        $result = $this->useCase->execute('João', 'joao@example.com', 'password123');

        $this->assertSame('jwt-token', $result['access_token']);
        $this->assertSame('bearer', $result['token_type']);
        $this->assertSame(3600, $result['expires_in']);
        $this->assertInstanceOf(User::class, $result['user']);
    }

    public function test_hashes_password_before_storing(): void
    {
        $this->repository->method('findByEmail')->willReturn(null);

        $this->hasher->expects($this->once())
            ->method('make')
            ->with('password123')
            ->willReturn('hashed_password');

        $user = (new User())->setName('João')->setEmail('joao@example.com');
        $this->repository->expects($this->once())
            ->method('create')
            ->with('João', 'joao@example.com', 'hashed_password')
            ->willReturn($user);

        $this->auth->method('loginByEmail')->willReturn('jwt-token');
        $this->auth->method('getTTL')->willReturn(3600);

        $this->useCase->execute('João', 'joao@example.com', 'password123');
    }

    public function test_throws_duplicate_email_exception_when_email_already_registered(): void
    {
        $this->expectException(DuplicateEmailException::class);

        $existing = (new User())->setName('Outro')->setEmail('joao@example.com');
        $this->repository->method('findByEmail')->willReturn($existing);

        $this->useCase->execute('João', 'joao@example.com', 'password123');
    }
}
