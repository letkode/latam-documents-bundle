<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Tests\Country\Paraguay;

use Letkode\LatamDocumentsBundle\Country\Paraguay\ParaguayCiDocument;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ParaguayCiDocumentTest extends TestCase
{
    private ParaguayCiDocument $doc;

    protected function setUp(): void
    {
        $this->doc = new ParaguayCiDocument();
    }

    public function testGetCountry(): void
    {
        self::assertSame(CountryDocumentEnum::PY, $this->doc->getCountry());
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
        self::assertSame('1234567', $this->doc->normalize('1234567'));
    }

    public function testFormatReturnsNormalized(): void
    {
        self::assertSame('1234567', $this->doc->format('1234567'));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function validCis(): array
    {
        return [
            '6 digitos (minimo)' => ['123456'],
            '7 digitos' => ['1234567'],
            '8 digitos (maximo)' => ['12345678'],
            'otro valido' => ['9876543'],
        ];
    }

    /**
     * @return array<string, array{string}>
     */
    public static function invalidCis(): array
    {
        return [
            'vacio' => [''],
            'muy corto' => ['12345'],
            'muy largo' => ['123456789'],
            'con letras' => ['12345A'],
        ];
    }
}
