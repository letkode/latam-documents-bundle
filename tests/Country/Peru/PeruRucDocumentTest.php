<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Tests\Country\Peru;

use Letkode\LatamDocumentsBundle\Country\Peru\PeruRucDocument;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class PeruRucDocumentTest extends TestCase
{
    private PeruRucDocument $doc;

    protected function setUp(): void
    {
        $this->doc = new PeruRucDocument();
    }

    public function testGetCountry(): void
    {
        self::assertSame(CountryDocumentEnum::PE, $this->doc->getCountry());
    }

    public function testGetType(): void
    {
        self::assertSame(DocumentTypeEnum::RUC, $this->doc->getType());
    }

    #[DataProvider('validRucs')]
    public function testIsValidReturnsTrue(string $raw): void
    {
        self::assertTrue($this->doc->isValid($raw));
    }

    #[DataProvider('invalidRucs')]
    public function testIsValidReturnsFalse(string $raw): void
    {
        self::assertFalse($this->doc->isValid($raw));
    }

    public function testNormalizeStripsNonDigits(): void
    {
        self::assertSame('10123456781', $this->doc->normalize('10123456781'));
    }

    public function testFormatReturnsNormalized(): void
    {
        self::assertSame('10123456781', $this->doc->format('10123456781'));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function validRucs(): array
    {
        return [
            'persona natural prefijo 10' => ['10123456781'],
            'empresa prefijo 20' => ['20123456786'],
            'prefijo 15' => ['15123456782'],
            'prefijo 17' => ['17123456785'],
        ];
    }

    /**
     * @return array<string, array{string}>
     */
    public static function invalidRucs(): array
    {
        return [
            'vacio' => [''],
            'prefijo invalido' => ['11123456789'],
            'dv incorrecto' => ['10123456782'],
            'muy corto' => ['1012345678'],
            'muy largo' => ['101234567810'],
        ];
    }
}
