<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Tests\Country\Brazil;

use Letkode\LatamDocumentsBundle\Country\Brazil\BrazilCpfDocument;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class BrazilCpfDocumentTest extends TestCase
{
    private BrazilCpfDocument $doc;

    protected function setUp(): void
    {
        $this->doc = new BrazilCpfDocument();
    }

    public function testGetCountry(): void
    {
        self::assertSame(CountryDocumentEnum::BR, $this->doc->getCountry());
    }

    public function testGetType(): void
    {
        self::assertSame(DocumentTypeEnum::CPF, $this->doc->getType());
    }

    #[DataProvider('validCpfs')]
    public function testIsValidReturnsTrue(string $raw): void
    {
        self::assertTrue($this->doc->isValid($raw));
    }

    #[DataProvider('invalidCpfs')]
    public function testIsValidReturnsFalse(string $raw): void
    {
        self::assertFalse($this->doc->isValid($raw));
    }

    public function testNormalizeStripsNonDigits(): void
    {
        self::assertSame('11144477735', $this->doc->normalize('111.444.777-35'));
    }

    public function testFormatAddsMascaraCpf(): void
    {
        self::assertSame('111.444.777-35', $this->doc->format('11144477735'));
    }

    public function testFormatFromAlreadyFormattedInput(): void
    {
        self::assertSame('111.444.777-35', $this->doc->format('111.444.777-35'));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function validCpfs(): array
    {
        return [
            'con puntos y guion' => ['111.444.777-35'],
            'solo digitos' => ['11144477735'],
            'otro valido' => ['52998224725'],
            'otro valido 2' => ['07493267006'],
        ];
    }

    /**
     * @return array<string, array{string}>
     */
    public static function invalidCpfs(): array
    {
        return [
            'todos iguales' => ['11111111111'],
            'digito incorrecto' => ['11144477734'],
            'muy corto' => ['1114447773'],
            'vacio' => [''],
        ];
    }
}
