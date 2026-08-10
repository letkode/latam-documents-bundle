<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Tests\Country\Peru;

use Letkode\LatamDocumentsBundle\Country\Peru\PeruDniDocument;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class PeruDniDocumentTest extends TestCase
{
    private PeruDniDocument $doc;

    protected function setUp(): void
    {
        $this->doc = new PeruDniDocument();
    }

    public function testGetCountry(): void
    {
        self::assertSame(CountryDocumentEnum::PE, $this->doc->getCountry());
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
        self::assertSame('12345678', $this->doc->normalize('12345678'));
    }

    public function testFormatReturnsNormalized(): void
    {
        self::assertSame('12345678', $this->doc->format('12345678'));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function validDnis(): array
    {
        return [
            '8 digitos exactos' => ['12345678'],
            'otro valido' => ['00000001'],
            'otro 2' => ['87654321'],
            'otro 3' => ['99999999'],
        ];
    }

    /**
     * @return array<string, array{string}>
     */
    public static function invalidDnis(): array
    {
        return [
            'vacio' => [''],
            'muy corto' => ['1234567'],
            'muy largo' => ['123456789'],
            'con letras' => ['1234567A'],
        ];
    }
}
