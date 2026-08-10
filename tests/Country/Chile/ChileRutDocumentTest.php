<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Tests\Country\Chile;

use Letkode\LatamDocumentsBundle\Country\Chile\ChileRutDocument;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ChileRutDocumentTest extends TestCase
{
    private ChileRutDocument $doc;

    protected function setUp(): void
    {
        $this->doc = new ChileRutDocument();
    }

    public function testGetCountry(): void
    {
        self::assertSame(CountryDocumentEnum::CL, $this->doc->getCountry());
    }

    public function testGetType(): void
    {
        self::assertSame(DocumentTypeEnum::RUT, $this->doc->getType());
    }

    #[DataProvider('validRuts')]
    public function testIsValidReturnsTrue(string $raw): void
    {
        self::assertTrue($this->doc->isValid($raw));
    }

    #[DataProvider('invalidRuts')]
    public function testIsValidReturnsFalse(string $raw): void
    {
        self::assertFalse($this->doc->isValid($raw));
    }

    public function testNormalizeStripsDotsAndDash(): void
    {
        self::assertSame('123456785', $this->doc->normalize('12.345.678-5'));
    }

    public function testNormalizeUppercasesK(): void
    {
        self::assertSame('7920000K', $this->doc->normalize('7.920.000-k'));
    }

    public function testFormatAddsDotsAndDash(): void
    {
        self::assertSame('12.345.678-5', $this->doc->format('123456785'));
    }

    public function testFormatFromAlreadyFormattedInput(): void
    {
        self::assertSame('12.345.678-5', $this->doc->format('12.345.678-5'));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function validRuts(): array
    {
        return [
            'con puntos y guion' => ['12.345.678-5'],
            'sin puntos con guion' => ['12345678-5'],
            'solo digitos' => ['123456785'],
            'verificador K mayuscula' => ['7.920.000-K'],
            'verificador k minuscula' => ['7.920.000-k'],
            'rut corto 7 digitos' => ['7654321-6'],
        ];
    }

    /**
     * @return array<string, array{string}>
     */
    public static function invalidRuts(): array
    {
        return [
            'digito verificador incorrecto' => ['12.345.678-0'],
            'vacio' => [''],
            'letras' => ['AB.CDE.FGH-I'],
            'demasiado corto' => ['123-4'],
            'dv incorrecto todos unos' => ['11.111.111-0'],
        ];
    }
}
