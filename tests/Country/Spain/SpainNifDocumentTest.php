<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Tests\Country\Spain;

use Letkode\LatamDocumentsBundle\Country\Spain\SpainNifDocument;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class SpainNifDocumentTest extends TestCase
{
    private SpainNifDocument $doc;

    protected function setUp(): void
    {
        $this->doc = new SpainNifDocument();
    }

    public function testGetCountry(): void
    {
        self::assertSame(CountryDocumentEnum::ES, $this->doc->getCountry());
    }

    public function testGetType(): void
    {
        self::assertSame(DocumentTypeEnum::NIF, $this->doc->getType());
    }

    #[DataProvider('validNifs')]
    public function testIsValidReturnsTrue(string $raw): void
    {
        self::assertTrue($this->doc->isValid($raw));
    }

    #[DataProvider('invalidNifs')]
    public function testIsValidReturnsFalse(string $raw): void
    {
        self::assertFalse($this->doc->isValid($raw));
    }

    public function testNormalizeUppercaseAndStripsSpaces(): void
    {
        self::assertSame('12345678Z', $this->doc->normalize('12345678z'));
    }

    public function testFormatAddsGuion(): void
    {
        self::assertSame('12345678-Z', $this->doc->format('12345678Z'));
    }

    public function testFormatFromAlreadyFormattedInput(): void
    {
        self::assertSame('12345678-Z', $this->doc->format('12345678-Z'));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function validNifs(): array
    {
        return [
            'letra Z correcta' => ['12345678Z'],
            'con guion' => ['12345678-Z'],
            'otro valido' => ['00000000T'],
            'otro valido 2' => ['99999999R'],
        ];
    }

    /**
     * @return array<string, array{string}>
     */
    public static function invalidNifs(): array
    {
        return [
            'vacio' => [''],
            'letra incorrecta' => ['12345678A'],
            'muy corto' => ['1234567Z'],
            'muy largo' => ['123456789Z'],
        ];
    }
}
