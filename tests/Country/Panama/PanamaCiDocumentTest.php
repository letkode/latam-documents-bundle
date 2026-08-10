<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Tests\Country\Panama;

use Letkode\LatamDocumentsBundle\Country\Panama\PanamaCiDocument;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class PanamaCiDocumentTest extends TestCase
{
    private PanamaCiDocument $doc;

    protected function setUp(): void
    {
        $this->doc = new PanamaCiDocument();
    }

    public function testGetCountry(): void
    {
        self::assertSame(CountryDocumentEnum::PA, $this->doc->getCountry());
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

    public function testNormalizePreservesFormat(): void
    {
        self::assertSame('8-123-4567', $this->doc->normalize('8-123-4567'));
    }

    public function testFormatReturnsNormalized(): void
    {
        self::assertSame('8-123-4567', $this->doc->format('8-123-4567'));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function validCis(): array
    {
        return [
            'formato N-NNN-NNNN' => ['8-123-4567'],
            'formato PE-NNN-NNNN' => ['PE-123-4567'],
            'formato E-NNN-NNNN' => ['E-123-4567'],
            'formato NN-NNN-NNNN' => ['12-123-4567'],
        ];
    }

    /**
     * @return array<string, array{string}>
     */
    public static function invalidCis(): array
    {
        return [
            'vacio' => [''],
            'muy corto' => ['1234567'],
            'prefijo 3 letras' => ['ABC-123-4567'],
            'solo digitos largo' => ['12345678901'],
        ];
    }
}
