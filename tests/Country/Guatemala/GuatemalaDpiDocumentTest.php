<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Tests\Country\Guatemala;

use Letkode\LatamDocumentsBundle\Country\Guatemala\GuatemalaDpiDocument;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class GuatemalaDpiDocumentTest extends TestCase
{
    private GuatemalaDpiDocument $doc;

    protected function setUp(): void
    {
        $this->doc = new GuatemalaDpiDocument();
    }

    public function testGetCountry(): void
    {
        self::assertSame(CountryDocumentEnum::GT, $this->doc->getCountry());
    }

    public function testGetType(): void
    {
        self::assertSame(DocumentTypeEnum::DPI, $this->doc->getType());
    }

    #[DataProvider('validDpis')]
    public function testIsValidReturnsTrue(string $raw): void
    {
        self::assertTrue($this->doc->isValid($raw));
    }

    #[DataProvider('invalidDpis')]
    public function testIsValidReturnsFalse(string $raw): void
    {
        self::assertFalse($this->doc->isValid($raw));
    }

    public function testNormalizeStripsNonDigits(): void
    {
        self::assertSame('1234567890123', $this->doc->normalize('1234567890123'));
    }

    public function testFormatAddsEspacios(): void
    {
        self::assertSame('1234 56789 0123', $this->doc->format('1234567890123'));
    }

    public function testFormatFromAlreadyFormattedInput(): void
    {
        self::assertSame('1234 56789 0123', $this->doc->format('1234 56789 0123'));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function validDpis(): array
    {
        return [
            '13 digitos exactos' => ['1234567890123'],
            'otro valido' => ['0000000000001'],
            'otro 2' => ['9999999999999'],
            'otro 3' => ['1111111111111'],
        ];
    }

    /**
     * @return array<string, array{string}>
     */
    public static function invalidDpis(): array
    {
        return [
            'vacio' => [''],
            'muy corto' => ['123456789012'],
            'muy largo' => ['12345678901234'],
            'con letras' => ['123456789012A'],
        ];
    }
}
