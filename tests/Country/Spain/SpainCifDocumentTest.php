<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Tests\Country\Spain;

use Letkode\LatamDocumentsBundle\Country\Spain\SpainCifDocument;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class SpainCifDocumentTest extends TestCase
{
    private SpainCifDocument $doc;

    protected function setUp(): void
    {
        $this->doc = new SpainCifDocument();
    }

    public function testGetCountry(): void
    {
        self::assertSame(CountryDocumentEnum::ES, $this->doc->getCountry());
    }

    public function testGetType(): void
    {
        self::assertSame(DocumentTypeEnum::CIF, $this->doc->getType());
    }

    #[DataProvider('validCifs')]
    public function testIsValidReturnsTrue(string $raw): void
    {
        self::assertTrue($this->doc->isValid($raw));
    }

    #[DataProvider('invalidCifs')]
    public function testIsValidReturnsFalse(string $raw): void
    {
        self::assertFalse($this->doc->isValid($raw));
    }

    public function testNormalizeUppercase(): void
    {
        self::assertSame('A12345674', $this->doc->normalize('a12345674'));
    }

    public function testFormatReturnsNormalized(): void
    {
        self::assertSame('A12345674', $this->doc->format('A12345674'));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function validCifs(): array
    {
        return [
            'tipo A con digito' => ['A12345674'],
            'tipo A con letra' => ['A1234567D'],
            'tipo B' => ['B12345674'],
            'tipo P solo letra' => ['P1234567D'],
        ];
    }

    /**
     * @return array<string, array{string}>
     */
    public static function invalidCifs(): array
    {
        return [
            'vacio' => [''],
            'control incorrecto' => ['A12345678'],
            'prefijo invalido I' => ['I12345674'],
            'muy corto' => ['A123456'],
        ];
    }
}
