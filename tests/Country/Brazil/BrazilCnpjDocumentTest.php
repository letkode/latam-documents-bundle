<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Tests\Country\Brazil;

use Letkode\LatamDocumentsBundle\Country\Brazil\BrazilCnpjDocument;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class BrazilCnpjDocumentTest extends TestCase
{
    private BrazilCnpjDocument $doc;

    protected function setUp(): void
    {
        $this->doc = new BrazilCnpjDocument();
    }

    public function testGetCountry(): void
    {
        self::assertSame(CountryDocumentEnum::BR, $this->doc->getCountry());
    }

    public function testGetType(): void
    {
        self::assertSame(DocumentTypeEnum::CNPJ, $this->doc->getType());
    }

    #[DataProvider('validCnpjs')]
    public function testIsValidReturnsTrue(string $raw): void
    {
        self::assertTrue($this->doc->isValid($raw));
    }

    #[DataProvider('invalidCnpjs')]
    public function testIsValidReturnsFalse(string $raw): void
    {
        self::assertFalse($this->doc->isValid($raw));
    }

    public function testNormalizeStripsNonDigits(): void
    {
        self::assertSame('11222333000181', $this->doc->normalize('11.222.333/0001-81'));
    }

    public function testFormatAddsMascaraCnpj(): void
    {
        self::assertSame('11.222.333/0001-81', $this->doc->format('11222333000181'));
    }

    public function testFormatFromAlreadyFormattedInput(): void
    {
        self::assertSame('11.222.333/0001-81', $this->doc->format('11.222.333/0001-81'));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function validCnpjs(): array
    {
        return [
            'con mascara' => ['11.222.333/0001-81'],
            'solo digitos' => ['11222333000181'],
            'otro valido' => ['45997418000153'],
            'otro valido 2' => ['11444777000161'],
        ];
    }

    /**
     * @return array<string, array{string}>
     */
    public static function invalidCnpjs(): array
    {
        return [
            'todos iguales' => ['11111111111111'],
            'dv incorrecto' => ['11222333000182'],
            'muy corto' => ['1122233300018'],
            'vacio' => [''],
        ];
    }
}
