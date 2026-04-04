<?php

namespace Tests\Unit\Application\Auth;

use App\Application\Contracts\JwtAuthInterface;
use App\Application\UseCases\Auth\LoginUserUseCase;
use App\Domain\Exceptions\InvalidCredentialsException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class LoginUserUseCaseTest extends TestCase
{
    private JwtAuthInterface&MockObject $auth;
    private LoginUserUseCase $useCase;

    protected function setUp(): void
    {
        $this->auth    = $this->createMock(JwtAuthInterface::class);
        $this->useCase = new LoginUserUseCase($this->auth);
    }

    public function test_returns_token_payload_on_success(): void
    {
        $this->auth->method('attempt')->willReturn('jwt-token');
        $this->auth->method('getTTL')->willReturn(3600);
        $this->auth->method('getCurrentUser')->willReturn(['id' => 1, 'email' => 'joao@example.com']);

        $result = $this->useCase->execute('joao@example.com', 'password123');

        $this->assertSame('jwt-token', $result['access_token']);
        $this->assertSame('bearer', $result['token_type']);
        $this->assertSame(3600, $result['expires_in']);
        $this->assertNotNull($result['user']);
    }

    public function test_throws_invalid_credentials_exception_when_credentials_are_wrong(): void
    {
        $this->expectException(InvalidCredentialsException::class);

        $this->auth->method('attempt')->willReturn(false);

        $this->useCase->execute('joao@example.com', 'wrong_password');
    }
}
