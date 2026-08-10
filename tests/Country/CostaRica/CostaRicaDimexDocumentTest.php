<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Tests\Country\CostaRica;

use Letkode\LatamDocumentsBundle\Country\CostaRica\CostaRicaDimexDocument;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class CostaRicaDimexDocumentTest extends TestCase
{
    private CostaRicaDimexDocument $doc;

    protected function setUp(): void
    {
        $this->doc = new CostaRicaDimexDocument();
    }

    public function testGetCountry(): void
    {
        self::assertSame(CountryDocumentEnum::CR, $this->doc->getCountry());
    }

    public function testGetType(): void
    {
        self::assertSame(DocumentTypeEnum::DIMEX, $this->doc->getType());
    }

    #[DataProvider('validDimexes')]
    public function testIsValidReturnsTrue(string $raw): void
    {
        self::assertTrue($this->doc->isValid($raw));
    }

    #[DataProvider('invalidDimexes')]
    public function testIsValidReturnsFalse(string $raw): void
    {
        self::assertFalse($this->doc->isValid($raw));
    }

    public function testNormalizeStripsNonDigits(): void
    {
        self::assertSame('10112345678', $this->doc->normalize('10112345678'));
    }

    public function testFormatReturnsNormalized(): void
    {
        self::assertSame('10112345678', $this->doc->format('10112345678'));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function validDimexes(): array
    {
        return [
            '11 digitos (minimo)' => ['10112345678'],
            '12 digitos (maximo)' => ['101123456789'],
            'otro 11 digitos' => ['11234567890'],
            'otro 12 digitos' => ['112345678901'],
        ];
    }

    /**
     * @return array<string, array{string}>
     */
    public static function invalidDimexes(): array
    {
        return [
            'vacio' => [''],
            'muy corto' => ['1234567890'],
            'muy largo' => ['1234567890123'],
            'con letras' => ['1234567890A'],
        ];
    }
}
