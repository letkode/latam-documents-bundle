<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Tests\Country\Ecuador;

use Letkode\LatamDocumentsBundle\Country\Ecuador\EcuadorCiDocument;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class EcuadorCiDocumentTest extends TestCase
{
    private EcuadorCiDocument $doc;

    protected function setUp(): void
    {
        $this->doc = new EcuadorCiDocument();
    }

    public function testGetCountry(): void
    {
        self::assertSame(CountryDocumentEnum::EC, $this->doc->getCountry());
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
        self::assertSame('0100000009', $this->doc->normalize('0100000009'));
    }

    public function testFormatReturnsNormalized(): void
    {
        self::assertSame('0100000009', $this->doc->format('0100000009'));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function validCis(): array
    {
        return [
            'provincia 01 valida' => ['0100000009'],
            'provincia 02' => ['0200000008'],
            'provincia 24' => ['2400000002'],
            'otro valido' => ['1710034065'],
        ];
    }

    /**
     * @return array<string, array{string}>
     */
    public static function invalidCis(): array
    {
        return [
            'vacio' => [''],
            'provincia 00' => ['0000000000'],
            'provincia 25' => ['2500000000'],
            'dv incorrecto' => ['0100000001'],
            'muy corto' => ['010000000'],
        ];
    }
}
