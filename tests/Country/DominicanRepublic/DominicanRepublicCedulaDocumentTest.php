<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Tests\Country\DominicanRepublic;

use Letkode\LatamDocumentsBundle\Country\DominicanRepublic\DominicanRepublicCedulaDocument;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class DominicanRepublicCedulaDocumentTest extends TestCase
{
    private DominicanRepublicCedulaDocument $doc;

    protected function setUp(): void
    {
        $this->doc = new DominicanRepublicCedulaDocument();
    }

    public function testGetCountry(): void
    {
        self::assertSame(CountryDocumentEnum::DO, $this->doc->getCountry());
    }

    public function testGetType(): void
    {
        self::assertSame(DocumentTypeEnum::CEDULA, $this->doc->getType());
    }

    #[DataProvider('validCedulas')]
    public function testIsValidReturnsTrue(string $raw): void
    {
        self::assertTrue($this->doc->isValid($raw));
    }

    #[DataProvider('invalidCedulas')]
    public function testIsValidReturnsFalse(string $raw): void
    {
        self::assertFalse($this->doc->isValid($raw));
    }

    public function testNormalizeStripsNonDigits(): void
    {
        self::assertSame('00100000009', $this->doc->normalize('001-0000000-9'));
    }

    public function testFormatAddsGuiones(): void
    {
        self::assertSame('001-0000000-9', $this->doc->format('00100000009'));
    }

    public function testFormatFromAlreadyFormattedInput(): void
    {
        self::assertSame('001-0000000-9', $this->doc->format('001-0000000-9'));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function validCedulas(): array
    {
        return [
            'valida con dv 9' => ['00100000009'],
            'con guiones' => ['001-0000000-9'],
            'otro valido' => ['22400022418'],
            'otro valido 2' => ['40212776047'],
        ];
    }

    /**
     * @return array<string, array{string}>
     */
    public static function invalidCedulas(): array
    {
        return [
            'vacio' => [''],
            'dv incorrecto' => ['00100000001'],
            'muy corto' => ['0010000000'],
            'muy largo' => ['001000000090'],
        ];
    }
}
