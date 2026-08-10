<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Tests\Country\ElSalvador;

use Letkode\LatamDocumentsBundle\Country\ElSalvador\ElSalvadorDuiDocument;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ElSalvadorDuiDocumentTest extends TestCase
{
    private ElSalvadorDuiDocument $doc;

    protected function setUp(): void
    {
        $this->doc = new ElSalvadorDuiDocument();
    }

    public function testGetCountry(): void
    {
        self::assertSame(CountryDocumentEnum::SV, $this->doc->getCountry());
    }

    public function testGetType(): void
    {
        self::assertSame(DocumentTypeEnum::DUI, $this->doc->getType());
    }

    #[DataProvider('validDuis')]
    public function testIsValidReturnsTrue(string $raw): void
    {
        self::assertTrue($this->doc->isValid($raw));
    }

    #[DataProvider('invalidDuis')]
    public function testIsValidReturnsFalse(string $raw): void
    {
        self::assertFalse($this->doc->isValid($raw));
    }

    public function testNormalizeStripsNonDigits(): void
    {
        self::assertSame('012345676', $this->doc->normalize('01234567-6'));
    }

    public function testFormatAddsGuion(): void
    {
        self::assertSame('01234567-6', $this->doc->format('012345676'));
    }

    public function testFormatFromAlreadyFormattedInput(): void
    {
        self::assertSame('01234567-6', $this->doc->format('01234567-6'));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function validDuis(): array
    {
        return [
            'sin guion' => ['012345676'],
            'con guion' => ['01234567-6'],
            'todos ceros' => ['000000000'],
            'otro valido' => ['012345676'],
        ];
    }

    /**
     * @return array<string, array{string}>
     */
    public static function invalidDuis(): array
    {
        return [
            'vacio' => [''],
            'dv incorrecto' => ['012345677'],
            'muy corto' => ['01234567'],
            'muy largo' => ['0123456760'],
        ];
    }
}
