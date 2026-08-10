<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Tests\Country\Colombia;

use Letkode\LatamDocumentsBundle\Country\Colombia\ColombiaNitDocument;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ColombiaNitDocumentTest extends TestCase
{
    private ColombiaNitDocument $doc;

    protected function setUp(): void
    {
        $this->doc = new ColombiaNitDocument();
    }

    public function testGetCountry(): void
    {
        self::assertSame(CountryDocumentEnum::CO, $this->doc->getCountry());
    }

    public function testGetType(): void
    {
        self::assertSame(DocumentTypeEnum::NIT, $this->doc->getType());
    }

    #[DataProvider('validNits')]
    public function testIsValidReturnsTrue(string $raw): void
    {
        self::assertTrue($this->doc->isValid($raw));
    }

    #[DataProvider('invalidNits')]
    public function testIsValidReturnsFalse(string $raw): void
    {
        self::assertFalse($this->doc->isValid($raw));
    }

    public function testNormalizeStripsNonDigits(): void
    {
        self::assertSame('9000000005', $this->doc->normalize('900000000-5'));
    }

    public function testFormatAddsDash(): void
    {
        self::assertSame('900000000-5', $this->doc->format('9000000005'));
    }

    public function testFormatFromAlreadyFormattedInput(): void
    {
        self::assertSame('900000000-5', $this->doc->format('900000000-5'));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function validNits(): array
    {
        return [
            '10 digitos con dv' => ['9000000005'],
            'con guion' => ['900000000-5'],
            '9 digitos sin dv' => ['900000000'],
            'otro valido 10 dig' => ['1234567896'],
        ];
    }

    /**
     * @return array<string, array{string}>
     */
    public static function invalidNits(): array
    {
        return [
            'vacio' => [''],
            'muy corto' => ['12345678'],
            'muy largo' => ['12345678901'],
            'dv incorrecto' => ['9000000006'],
        ];
    }
}
