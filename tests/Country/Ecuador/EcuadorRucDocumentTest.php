<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Tests\Country\Ecuador;

use Letkode\LatamDocumentsBundle\Country\Ecuador\EcuadorRucDocument;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class EcuadorRucDocumentTest extends TestCase
{
    private EcuadorRucDocument $doc;

    protected function setUp(): void
    {
        $this->doc = new EcuadorRucDocument();
    }

    public function testGetCountry(): void
    {
        self::assertSame(CountryDocumentEnum::EC, $this->doc->getCountry());
    }

    public function testGetType(): void
    {
        self::assertSame(DocumentTypeEnum::RUC, $this->doc->getType());
    }

    #[DataProvider('validRucs')]
    public function testIsValidReturnsTrue(string $raw): void
    {
        self::assertTrue($this->doc->isValid($raw));
    }

    #[DataProvider('invalidRucs')]
    public function testIsValidReturnsFalse(string $raw): void
    {
        self::assertFalse($this->doc->isValid($raw));
    }

    public function testNormalizeStripsNonDigits(): void
    {
        self::assertSame('0100000009001', $this->doc->normalize('0100000009001'));
    }

    public function testFormatReturnsNormalized(): void
    {
        self::assertSame('0100000009001', $this->doc->format('0100000009001'));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function validRucs(): array
    {
        return [
            'persona natural sufijo 001' => ['0100000009001'],
            'persona natural sufijo 002' => ['0100000009002'],
            'otro valido' => ['1710034065001'],
            'sufijo diferente de 001' => ['0200000008003'],
        ];
    }

    /**
     * @return array<string, array{string}>
     */
    public static function invalidRucs(): array
    {
        return [
            'vacio' => [''],
            'sufijo 000' => ['0100000009000'],
            'ci base invalida' => ['0100000001001'],
            'muy corto' => ['010000000900'],
            'muy largo' => ['01000000090011'],
        ];
    }
}
