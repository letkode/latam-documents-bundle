<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Tests\Country\Honduras;

use Letkode\LatamDocumentsBundle\Country\Honduras\HondurasRnpDocument;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class HondurasRnpDocumentTest extends TestCase
{
    private HondurasRnpDocument $doc;

    protected function setUp(): void
    {
        $this->doc = new HondurasRnpDocument();
    }

    public function testGetCountry(): void
    {
        self::assertSame(CountryDocumentEnum::HN, $this->doc->getCountry());
    }

    public function testGetType(): void
    {
        self::assertSame(DocumentTypeEnum::RNP, $this->doc->getType());
    }

    #[DataProvider('validRnps')]
    public function testIsValidReturnsTrue(string $raw): void
    {
        self::assertTrue($this->doc->isValid($raw));
    }

    #[DataProvider('invalidRnps')]
    public function testIsValidReturnsFalse(string $raw): void
    {
        self::assertFalse($this->doc->isValid($raw));
    }

    public function testNormalizeStripsNonDigits(): void
    {
        self::assertSame('0101198012345', $this->doc->normalize('0101-1980-12345'));
    }

    public function testFormatAddsGuiones(): void
    {
        self::assertSame('0101-1980-12345', $this->doc->format('0101198012345'));
    }

    public function testFormatFromAlreadyFormattedInput(): void
    {
        self::assertSame('0101-1980-12345', $this->doc->format('0101-1980-12345'));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function validRnps(): array
    {
        return [
            '13 digitos exactos' => ['0101198012345'],
            'con guiones' => ['0101-1980-12345'],
            'otro valido' => ['1234567890123'],
            'otro 2' => ['9999999999999'],
        ];
    }

    /**
     * @return array<string, array{string}>
     */
    public static function invalidRnps(): array
    {
        return [
            'vacio' => [''],
            'muy corto' => ['010119801234'],
            'muy largo' => ['01011980123456'],
            'con letras' => ['010119801234A'],
        ];
    }
}
