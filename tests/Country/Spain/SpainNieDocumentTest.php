<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Tests\Country\Spain;

use Letkode\LatamDocumentsBundle\Country\Spain\SpainNieDocument;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class SpainNieDocumentTest extends TestCase
{
    private SpainNieDocument $doc;

    protected function setUp(): void
    {
        $this->doc = new SpainNieDocument();
    }

    public function testGetCountry(): void
    {
        self::assertSame(CountryDocumentEnum::ES, $this->doc->getCountry());
    }

    public function testGetType(): void
    {
        self::assertSame(DocumentTypeEnum::NIE, $this->doc->getType());
    }

    #[DataProvider('validNies')]
    public function testIsValidReturnsTrue(string $raw): void
    {
        self::assertTrue($this->doc->isValid($raw));
    }

    #[DataProvider('invalidNies')]
    public function testIsValidReturnsFalse(string $raw): void
    {
        self::assertFalse($this->doc->isValid($raw));
    }

    public function testNormalizeUppercase(): void
    {
        self::assertSame('X1234567L', $this->doc->normalize('x1234567l'));
    }

    public function testFormatAddsGuiones(): void
    {
        self::assertSame('X-1234567-L', $this->doc->format('X1234567L'));
    }

    public function testFormatFromAlreadyFormattedInput(): void
    {
        self::assertSame('X-1234567-L', $this->doc->format('X-1234567-L'));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function validNies(): array
    {
        return [
            'prefijo X' => ['X1234567L'],
            'prefijo Y' => ['Y1234567X'],
            'prefijo Z' => ['Z1234567R'],
            'con guiones' => ['X-1234567-L'],
        ];
    }

    /**
     * @return array<string, array{string}>
     */
    public static function invalidNies(): array
    {
        return [
            'vacio' => [''],
            'prefijo invalido A' => ['A1234567L'],
            'letra incorrecta' => ['X1234567A'],
            'muy corto' => ['X123456L'],
        ];
    }
}
