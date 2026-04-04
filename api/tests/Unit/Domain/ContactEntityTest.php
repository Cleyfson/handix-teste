<?php

namespace Tests\Unit\Domain;

use App\Domain\Entities\Contact;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ContactEntityTest extends TestCase
{
    public function test_from_array_hydrates_correctly(): void
    {
        $contact = Contact::fromArray([
            'name'  => 'João Silva',
            'email' => 'joao@example.com',
            'phone' => '11912345678',
            'notes' => 'Observação',
        ]);

        $this->assertSame('João Silva', $contact->getName());
        $this->assertSame('joao@example.com', $contact->getEmail());
        $this->assertSame('11912345678', $contact->getPhone());
        $this->assertSame('Observação', $contact->getNotes());
        $this->assertNull($contact->getId());
    }

    public function test_set_name_trims_whitespace(): void
    {
        $contact = Contact::fromArray(['name' => '  Maria  ', 'email' => 'maria@example.com']);

        $this->assertSame('Maria', $contact->getName());
    }

    public function test_set_name_throws_for_empty_string(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Contact::fromArray(['name' => '   ', 'email' => 'a@b.com']);
    }

    public function test_set_name_throws_when_exceeds_255_chars(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Contact::fromArray(['name' => str_repeat('a', 256), 'email' => 'a@b.com']);
    }

    public function test_set_email_throws_for_invalid_format(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Contact::fromArray(['name' => 'Fulano', 'email' => 'not-an-email']);
    }

    public function test_json_serialize_returns_expected_structure(): void
    {
        $contact = (new Contact())
            ->setId(1)
            ->setName('Ana')
            ->setEmail('ana@example.com')
            ->setPhone(null)
            ->setNotes(null)
            ->setCreatedAt('2026-01-01T00:00:00+00:00')
            ->setUpdatedAt('2026-01-01T00:00:00+00:00');

        $json = $contact->jsonSerialize();

        $this->assertArrayHasKey('id', $json);
        $this->assertArrayHasKey('name', $json);
        $this->assertArrayHasKey('email', $json);
        $this->assertArrayHasKey('phone', $json);
        $this->assertArrayHasKey('notes', $json);
        $this->assertArrayHasKey('created_at', $json);
        $this->assertArrayHasKey('updated_at', $json);
        $this->assertSame(1, $json['id']);
        $this->assertSame('Ana', $json['name']);
    }
}
