<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Tests;

use Letkode\LatamDocumentsBundle\DocumentFactory;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;
use Letkode\LatamDocumentsBundle\Exception\UnsupportedDocumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class DocumentFactoryTest extends TestCase
{
    private DocumentFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new DocumentFactory();
    }

    #[DataProvider('supportedCombinations')]
    public function testMakeReturnDocumentInterface(CountryDocumentEnum $country, DocumentTypeEnum $type): void
    {
        $doc = $this->factory->make($country, $type);
        self::assertSame($country, $doc->getCountry());
        self::assertSame($type, $doc->getType());
    }

    public function testMakeThrowsUnsupportedDocumentException(): void
    {
        $this->expectException(UnsupportedDocumentException::class);
        $this->factory->make(CountryDocumentEnum::CL, DocumentTypeEnum::CNPJ);
    }

    public function testSupportsReturnsTrueForValidCombination(): void
    {
        self::assertTrue($this->factory->supports(CountryDocumentEnum::CL, DocumentTypeEnum::RUT));
    }

    public function testSupportsReturnsFalseForInvalidCombination(): void
    {
        self::assertFalse($this->factory->supports(CountryDocumentEnum::CL, DocumentTypeEnum::CNPJ));
    }

    /**
     * @return array<string, array{CountryDocumentEnum, DocumentTypeEnum}>
     */
    public static function supportedCombinations(): array
    {
        return [
            'CL/RUT' => [CountryDocumentEnum::CL, DocumentTypeEnum::RUT],
            'BR/CPF' => [CountryDocumentEnum::BR, DocumentTypeEnum::CPF],
            'BR/CNPJ' => [CountryDocumentEnum::BR, DocumentTypeEnum::CNPJ],
            'AR/DNI' => [CountryDocumentEnum::AR, DocumentTypeEnum::DNI],
            'AR/CUIL' => [CountryDocumentEnum::AR, DocumentTypeEnum::CUIL],
            'AR/CUIT' => [CountryDocumentEnum::AR, DocumentTypeEnum::CUIT],
            'CO/CC' => [CountryDocumentEnum::CO, DocumentTypeEnum::CC],
            'CO/NIT' => [CountryDocumentEnum::CO, DocumentTypeEnum::NIT],
            'MX/CURP' => [CountryDocumentEnum::MX, DocumentTypeEnum::CURP],
            'MX/RFC' => [CountryDocumentEnum::MX, DocumentTypeEnum::RFC],
            'PE/DNI' => [CountryDocumentEnum::PE, DocumentTypeEnum::DNI],
            'PE/RUC' => [CountryDocumentEnum::PE, DocumentTypeEnum::RUC],
            'UY/CI' => [CountryDocumentEnum::UY, DocumentTypeEnum::CI],
            'EC/CI' => [CountryDocumentEnum::EC, DocumentTypeEnum::CI],
            'EC/RUC' => [CountryDocumentEnum::EC, DocumentTypeEnum::RUC],
            'DO/CEDULA' => [CountryDocumentEnum::DO, DocumentTypeEnum::CEDULA],
            'SV/DUI' => [CountryDocumentEnum::SV, DocumentTypeEnum::DUI],
            'ES/NIF' => [CountryDocumentEnum::ES, DocumentTypeEnum::NIF],
            'ES/NIE' => [CountryDocumentEnum::ES, DocumentTypeEnum::NIE],
            'ES/CIF' => [CountryDocumentEnum::ES, DocumentTypeEnum::CIF],
            'BO/CI' => [CountryDocumentEnum::BO, DocumentTypeEnum::CI],
            'PY/CI' => [CountryDocumentEnum::PY, DocumentTypeEnum::CI],
            'VE/CI' => [CountryDocumentEnum::VE, DocumentTypeEnum::CI],
            'VE/RIF' => [CountryDocumentEnum::VE, DocumentTypeEnum::RIF],
            'CR/CI' => [CountryDocumentEnum::CR, DocumentTypeEnum::CI],
            'CR/DIMEX' => [CountryDocumentEnum::CR, DocumentTypeEnum::DIMEX],
            'GT/DPI' => [CountryDocumentEnum::GT, DocumentTypeEnum::DPI],
            'HN/RNP' => [CountryDocumentEnum::HN, DocumentTypeEnum::RNP],
            'NI/CI' => [CountryDocumentEnum::NI, DocumentTypeEnum::CI],
            'PA/CI' => [CountryDocumentEnum::PA, DocumentTypeEnum::CI],
            'CU/CI' => [CountryDocumentEnum::CU, DocumentTypeEnum::CI],
        ];
    }
}
