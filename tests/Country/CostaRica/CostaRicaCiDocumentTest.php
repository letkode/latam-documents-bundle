<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Tests\Country\CostaRica;

use Letkode\LatamDocumentsBundle\Country\CostaRica\CostaRicaCiDocument;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class CostaRicaCiDocumentTest extends TestCase
{
    private CostaRicaCiDocument $doc;

    protected function setUp(): void
    {
        $this->doc = new CostaRicaCiDocument();
    }

    public function testGetCountry(): void
    {
        self::assertSame(CountryDocumentEnum::CR, $this->doc->getCountry());
    }

    public function testGetType(): void
    {
        self::assertSame(DocumentTypeEnum::CI, $this->doc->getType());
    }

    #[DataProvider('validCis')]
    public function testIsValidReturnsTrue(string $raw): void
    {
        self::assertTrue($this->doc->isValid($raw));
    }

    #[DataProvider('invalidCis')]
    public function testIsValidReturnsFalse(string $raw): void
    {
        self::assertFalse($this->doc->isValid($raw));
    }

    public function testNormalizeStripsNonDigits(): void
    {
        self::assertSame('101110123', $this->doc->normalize('1-0111-0123'));
    }

    public function testFormatAddsGuiones(): void
    {
        self::assertSame('1-0111-0123', $this->doc->format('101110123'));
    }

    public function testFormatFromAlreadyFormattedInput(): void
    {
        self::assertSame('1-0111-0123', $this->doc->format('1-0111-0123'));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function validCis(): array
    {
        return [
            '9 digitos exactos' => ['101110123'],
            'con guiones' => ['1-0111-0123'],
            'otro valido' => ['123456789'],
            'otro valido 2' => ['900000001'],
        ];
    }

    /**
     * @return array<string, array{string}>
     */
    public static function invalidCis(): array
    {
        return [
            'vacio' => [''],
            'muy corto' => ['12345678'],
            'muy largo' => ['1234567890'],
            'con letras' => ['12345678A'],
        ];
    }
}
