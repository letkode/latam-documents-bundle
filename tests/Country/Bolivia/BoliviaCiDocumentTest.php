<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Tests\Country\Bolivia;

use Letkode\LatamDocumentsBundle\Country\Bolivia\BoliviaCiDocument;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class BoliviaCiDocumentTest extends TestCase
{
    private BoliviaCiDocument $doc;

    protected function setUp(): void
    {
        $this->doc = new BoliviaCiDocument();
    }

    public function testGetCountry(): void
    {
        self::assertSame(CountryDocumentEnum::BO, $this->doc->getCountry());
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
            '5 digitos (minimo)' => ['12345'],
            '7 digitos' => ['1234567'],
            '10 digitos (maximo)' => ['1234567890'],
            '6 digitos' => ['123456'],
        ];
    }

    /**
     * @return array<string, array{string}>
     */
    public static function invalidCis(): array
    {
        return [
            'vacio' => [''],
            'muy corto' => ['1234'],
            'muy largo' => ['12345678901'],
            'con letras' => ['1234A'],
        ];
    }
}
