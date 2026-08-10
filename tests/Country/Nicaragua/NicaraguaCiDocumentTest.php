<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Tests\Country\Nicaragua;

use Letkode\LatamDocumentsBundle\Country\Nicaragua\NicaraguaCiDocument;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class NicaraguaCiDocumentTest extends TestCase
{
    private NicaraguaCiDocument $doc;

    protected function setUp(): void
    {
        $this->doc = new NicaraguaCiDocument();
    }

    public function testGetCountry(): void
    {
        self::assertSame(CountryDocumentEnum::NI, $this->doc->getCountry());
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

    public function testNormalizeStripsGuiones(): void
    {
        self::assertSame('0011905160001A', $this->doc->normalize('001-190516-0001A'));
    }

    public function testFormatAddsGuiones(): void
    {
        self::assertSame('001-190516-0001A', $this->doc->format('0011905160001A'));
    }

    public function testFormatFromAlreadyFormattedInput(): void
    {
        self::assertSame('001-190516-0001A', $this->doc->format('001-190516-0001A'));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function validCis(): array
    {
        return [
            'con guiones' => ['001-190516-0001A'],
            'sin guiones' => ['0011905160001A'],
            'letra Z' => ['001-190516-0001Z'],
            'otro valido' => ['123-456789-0001B'],
        ];
    }

    /**
     * @return array<string, array{string}>
     */
    public static function invalidCis(): array
    {
        return [
            'vacio' => [''],
            'sin letra final' => ['001-190516-00012'],
            'muy corto' => ['001-190516-001A'],
            'letras en medio' => ['001-A90516-0001A'],
        ];
    }
}
