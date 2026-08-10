<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Tests\Country\Uruguay;

use Letkode\LatamDocumentsBundle\Country\Uruguay\UruguayCiDocument;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class UruguayCiDocumentTest extends TestCase
{
    private UruguayCiDocument $doc;

    protected function setUp(): void
    {
        $this->doc = new UruguayCiDocument();
    }

    public function testGetCountry(): void
    {
        self::assertSame(CountryDocumentEnum::UY, $this->doc->getCountry());
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
        self::assertSame('1234561', $this->doc->normalize('1.234.561'));
    }

    public function testFormatAddsFormatoUruguayo(): void
    {
        self::assertSame('0.123.456-1', $this->doc->format('01234561'));
    }

    public function testFormatFromAlreadyFormattedInput(): void
    {
        self::assertSame('0.123.456-1', $this->doc->format('0.123.456-1'));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function validCis(): array
    {
        return [
            'valido 7 digitos' => ['1234561'],
            'con formato uruguayo' => ['0.123.456-1'],
            'otro valido' => ['3456884'],
            '8 digitos con cero' => ['01234561'],
        ];
    }

    /**
     * @return array<string, array{string}>
     */
    public static function invalidCis(): array
    {
        return [
            'dv incorrecto' => ['1234562'],
            'letras normalizadas' => ['123456A'],
            'muy largo' => ['123456789'],
            'otro dv incorrecto' => ['3456885'],
        ];
    }
}
