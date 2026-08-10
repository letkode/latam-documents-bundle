<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Tests\Country\Argentina;

use Letkode\LatamDocumentsBundle\Country\Argentina\ArgentinaDniDocument;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ArgentinaDniDocumentTest extends TestCase
{
    private ArgentinaDniDocument $doc;

    protected function setUp(): void
    {
        $this->doc = new ArgentinaDniDocument();
    }

    public function testGetCountry(): void
    {
        self::assertSame(CountryDocumentEnum::AR, $this->doc->getCountry());
    }

    public function testGetType(): void
    {
        self::assertSame(DocumentTypeEnum::DNI, $this->doc->getType());
    }

    #[DataProvider('validDnis')]
    public function testIsValidReturnsTrue(string $raw): void
    {
        self::assertTrue($this->doc->isValid($raw));
    }

    #[DataProvider('invalidDnis')]
    public function testIsValidReturnsFalse(string $raw): void
    {
        self::assertFalse($this->doc->isValid($raw));
    }

    public function testNormalizeStripsNonDigits(): void
    {
        self::assertSame('12345678', $this->doc->normalize('12.345.678'));
    }

    public function testNormalizeSevenDigits(): void
    {
        self::assertSame('1234567', $this->doc->normalize('1.234.567'));
    }

    public function testFormatEightDigits(): void
    {
        self::assertSame('12.345.678', $this->doc->format('12345678'));
    }

    public function testFormatFromAlreadyFormattedInput(): void
    {
        self::assertSame('12.345.678', $this->doc->format('12.345.678'));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function validDnis(): array
    {
        return [
            '8 digitos' => ['12345678'],
            '7 digitos' => ['1234567'],
            'con puntos 8 dig' => ['12.345.678'],
            'con puntos 7 dig' => ['1.234.567'],
        ];
    }

    /**
     * @return array<string, array{string}>
     */
    public static function invalidDnis(): array
    {
        return [
            'vacio' => [''],
            'muy corto' => ['123456'],
            'muy largo' => ['123456789'],
            'solo letras' => ['ABCDEFGH'],
        ];
    }
}
