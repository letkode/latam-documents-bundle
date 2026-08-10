<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Tests\Country\Colombia;

use Letkode\LatamDocumentsBundle\Country\Colombia\ColombiaCcDocument;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ColombiaCcDocumentTest extends TestCase
{
    private ColombiaCcDocument $doc;

    protected function setUp(): void
    {
        $this->doc = new ColombiaCcDocument();
    }

    public function testGetCountry(): void
    {
        self::assertSame(CountryDocumentEnum::CO, $this->doc->getCountry());
    }

    public function testGetType(): void
    {
        self::assertSame(DocumentTypeEnum::CC, $this->doc->getType());
    }

    #[DataProvider('validCcs')]
    public function testIsValidReturnsTrue(string $raw): void
    {
        self::assertTrue($this->doc->isValid($raw));
    }

    #[DataProvider('invalidCcs')]
    public function testIsValidReturnsFalse(string $raw): void
    {
        self::assertFalse($this->doc->isValid($raw));
    }

    public function testNormalizeStripsNonDigits(): void
    {
        self::assertSame('1234567890', $this->doc->normalize('1234567890'));
    }

    public function testFormatReturnsNormalized(): void
    {
        self::assertSame('1234567890', $this->doc->format('1234567890'));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function validCcs(): array
    {
        return [
            '6 digitos (minimo)' => ['123456'],
            '8 digitos' => ['12345678'],
            '10 digitos (maximo)' => ['1234567890'],
            '7 digitos' => ['1234567'],
        ];
    }

    /**
     * @return array<string, array{string}>
     */
    public static function invalidCcs(): array
    {
        return [
            'vacio' => [''],
            'muy corto' => ['12345'],
            'muy largo' => ['12345678901'],
            'solo letras' => ['ABCDEFGHIJ'],
        ];
    }
}
