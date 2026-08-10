<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Tests\Country\Venezuela;

use Letkode\LatamDocumentsBundle\Country\Venezuela\VenezuelaRifDocument;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class VenezuelaRifDocumentTest extends TestCase
{
    private VenezuelaRifDocument $doc;

    protected function setUp(): void
    {
        $this->doc = new VenezuelaRifDocument();
    }

    public function testGetCountry(): void
    {
        self::assertSame(CountryDocumentEnum::VE, $this->doc->getCountry());
    }

    public function testGetType(): void
    {
        self::assertSame(DocumentTypeEnum::RIF, $this->doc->getType());
    }

    #[DataProvider('validRifs')]
    public function testIsValidReturnsTrue(string $raw): void
    {
        self::assertTrue($this->doc->isValid($raw));
    }

    #[DataProvider('invalidRifs')]
    public function testIsValidReturnsFalse(string $raw): void
    {
        self::assertFalse($this->doc->isValid($raw));
    }

    public function testNormalizeStripsGuiones(): void
    {
        self::assertSame('J0000000000', $this->doc->normalize('J-000000000-0'));
    }

    public function testFormatAddsGuiones(): void
    {
        self::assertSame('J-000000000-0', $this->doc->format('J0000000000'));
    }

    public function testFormatFromAlreadyFormattedInput(): void
    {
        self::assertSame('J-000000000-0', $this->doc->format('J-000000000-0'));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function validRifs(): array
    {
        return [
            'prefijo J con guiones' => ['J-000000000-0'],
            'prefijo G' => ['G-123456789-5'],
            'prefijo V' => ['V-123456789-1'],
            'prefijo E sin guiones' => ['E1234567890'],
        ];
    }

    /**
     * @return array<string, array{string}>
     */
    public static function invalidRifs(): array
    {
        return [
            'vacio' => [''],
            'prefijo invalido A' => ['A-000000000-0'],
            'muy corto 8 dig' => ['J-12345678-0'],
            'sin prefijo' => ['000000000-0'],
        ];
    }
}
