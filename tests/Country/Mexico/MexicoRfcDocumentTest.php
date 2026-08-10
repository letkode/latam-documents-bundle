<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Tests\Country\Mexico;

use Letkode\LatamDocumentsBundle\Country\Mexico\MexicoRfcDocument;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class MexicoRfcDocumentTest extends TestCase
{
    private MexicoRfcDocument $doc;

    protected function setUp(): void
    {
        $this->doc = new MexicoRfcDocument();
    }

    public function testGetCountry(): void
    {
        self::assertSame(CountryDocumentEnum::MX, $this->doc->getCountry());
    }

    public function testGetType(): void
    {
        self::assertSame(DocumentTypeEnum::RFC, $this->doc->getType());
    }

    #[DataProvider('validRfcs')]
    public function testIsValidReturnsTrue(string $raw): void
    {
        self::assertTrue($this->doc->isValid($raw));
    }

    #[DataProvider('invalidRfcs')]
    public function testIsValidReturnsFalse(string $raw): void
    {
        self::assertFalse($this->doc->isValid($raw));
    }

    public function testNormalizeUppercaseAndStripsSpaces(): void
    {
        self::assertSame('BAAD850101AAA', $this->doc->normalize('baad850101aaa'));
    }

    public function testFormatReturnsNormalized(): void
    {
        self::assertSame('BAAD850101AAA', $this->doc->format('BAAD850101AAA'));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function validRfcs(): array
    {
        return [
            'persona fisica 13 chars' => ['BAAD850101AAA'],
            'persona moral 12 chars' => ['ABC850101AAA'],
            'con &' => ['A&BC850101AAA'],
            'homoclave con digitos' => ['BAAD850101A1B'],
        ];
    }

    /**
     * @return array<string, array{string}>
     */
    public static function invalidRfcs(): array
    {
        return [
            'vacio' => [''],
            'muy corto' => ['BAAD850101AA'],
            'inicia con dig' => ['1AAD850101AAA'],
            'mes invalido' => ['BAAD851301AAA'],
        ];
    }
}
