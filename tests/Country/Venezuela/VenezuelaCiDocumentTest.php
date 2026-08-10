<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Tests\Country\Venezuela;

use Letkode\LatamDocumentsBundle\Country\Venezuela\VenezuelaCiDocument;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class VenezuelaCiDocumentTest extends TestCase
{
    private VenezuelaCiDocument $doc;

    protected function setUp(): void
    {
        $this->doc = new VenezuelaCiDocument();
    }

    public function testGetCountry(): void
    {
        self::assertSame(CountryDocumentEnum::VE, $this->doc->getCountry());
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

    public function testNormalizeStripsPrefixV(): void
    {
        self::assertSame('12345678', $this->doc->normalize('V-12345678'));
    }

    public function testNormalizeStripsPrefixE(): void
    {
        self::assertSame('1234567', $this->doc->normalize('E-1234567'));
    }

    public function testFormatAddsGuionV(): void
    {
        self::assertSame('V-12345678', $this->doc->format('V-12345678'));
    }

    public function testFormatAddsGuionE(): void
    {
        self::assertSame('E-1234567', $this->doc->format('E1234567'));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function validCis(): array
    {
        return [
            'prefijo V con guion 8 dig' => ['V-12345678'],
            'prefijo E con guion 7 dig' => ['E-1234567'],
            'prefijo V sin guion' => ['V12345678'],
            'prefijo V 6 dig' => ['V-123456'],
        ];
    }

    /**
     * @return array<string, array{string}>
     */
    public static function invalidCis(): array
    {
        return [
            'vacio' => [''],
            'sin prefijo' => ['12345678'],
            'prefijo invalido' => ['A-12345678'],
            'muy corto V' => ['V-12345'],
        ];
    }
}
