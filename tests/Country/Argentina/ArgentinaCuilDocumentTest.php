<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Tests\Country\Argentina;

use Letkode\LatamDocumentsBundle\Country\Argentina\ArgentinaCuilDocument;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ArgentinaCuilDocumentTest extends TestCase
{
    private ArgentinaCuilDocument $doc;

    protected function setUp(): void
    {
        $this->doc = new ArgentinaCuilDocument();
    }

    public function testGetCountry(): void
    {
        self::assertSame(CountryDocumentEnum::AR, $this->doc->getCountry());
    }

    public function testGetType(): void
    {
        self::assertSame(DocumentTypeEnum::CUIL, $this->doc->getType());
    }

    #[DataProvider('validCuils')]
    public function testIsValidReturnsTrue(string $raw): void
    {
        self::assertTrue($this->doc->isValid($raw));
    }

    #[DataProvider('invalidCuils')]
    public function testIsValidReturnsFalse(string $raw): void
    {
        self::assertFalse($this->doc->isValid($raw));
    }

    public function testNormalizeStripsNonDigits(): void
    {
        self::assertSame('20123456786', $this->doc->normalize('20-12345678-6'));
    }

    public function testFormatAddsDashes(): void
    {
        self::assertSame('20-12345678-6', $this->doc->format('20123456786'));
    }

    public function testFormatFromAlreadyFormattedInput(): void
    {
        self::assertSame('20-12345678-6', $this->doc->format('20-12345678-6'));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function validCuils(): array
    {
        return [
            'valido sin guiones' => ['20123456786'],
            'valido con guiones' => ['20-12345678-6'],
            'prefijo 27 dv 0' => ['27123456780'],
            'prefijo 23 dv 5' => ['23123456785'],
        ];
    }

    /**
     * @return array<string, array{string}>
     */
    public static function invalidCuils(): array
    {
        return [
            'vacio' => [''],
            'digito verificador malo' => ['20123456787'],
            'menos de 11 digitos' => ['2012345678'],
            'mas de 11 digitos' => ['201234567860'],
        ];
    }
}
