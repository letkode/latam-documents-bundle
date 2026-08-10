<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle;

use Letkode\LatamDocumentsBundle\Contract\DocumentInterface;
use Letkode\LatamDocumentsBundle\Country\Argentina\ArgentinaCuilDocument;
use Letkode\LatamDocumentsBundle\Country\Argentina\ArgentinaCuitDocument;
use Letkode\LatamDocumentsBundle\Country\Argentina\ArgentinaDniDocument;
use Letkode\LatamDocumentsBundle\Country\Bolivia\BoliviaCiDocument;
use Letkode\LatamDocumentsBundle\Country\Brazil\BrazilCnpjDocument;
use Letkode\LatamDocumentsBundle\Country\Brazil\BrazilCpfDocument;
use Letkode\LatamDocumentsBundle\Country\Chile\ChileRutDocument;
use Letkode\LatamDocumentsBundle\Country\Colombia\ColombiaCcDocument;
use Letkode\LatamDocumentsBundle\Country\Colombia\ColombiaNitDocument;
use Letkode\LatamDocumentsBundle\Country\CostaRica\CostaRicaCiDocument;
use Letkode\LatamDocumentsBundle\Country\CostaRica\CostaRicaDimexDocument;
use Letkode\LatamDocumentsBundle\Country\Cuba\CubaCiDocument;
use Letkode\LatamDocumentsBundle\Country\DominicanRepublic\DominicanRepublicCedulaDocument;
use Letkode\LatamDocumentsBundle\Country\Ecuador\EcuadorCiDocument;
use Letkode\LatamDocumentsBundle\Country\Ecuador\EcuadorRucDocument;
use Letkode\LatamDocumentsBundle\Country\ElSalvador\ElSalvadorDuiDocument;
use Letkode\LatamDocumentsBundle\Country\Guatemala\GuatemalaDpiDocument;
use Letkode\LatamDocumentsBundle\Country\Honduras\HondurasRnpDocument;
use Letkode\LatamDocumentsBundle\Country\Mexico\MexicoCurpDocument;
use Letkode\LatamDocumentsBundle\Country\Mexico\MexicoRfcDocument;
use Letkode\LatamDocumentsBundle\Country\Nicaragua\NicaraguaCiDocument;
use Letkode\LatamDocumentsBundle\Country\Panama\PanamaCiDocument;
use Letkode\LatamDocumentsBundle\Country\Paraguay\ParaguayCiDocument;
use Letkode\LatamDocumentsBundle\Country\Peru\PeruDniDocument;
use Letkode\LatamDocumentsBundle\Country\Peru\PeruRucDocument;
use Letkode\LatamDocumentsBundle\Country\Spain\SpainCifDocument;
use Letkode\LatamDocumentsBundle\Country\Spain\SpainNieDocument;
use Letkode\LatamDocumentsBundle\Country\Spain\SpainNifDocument;
use Letkode\LatamDocumentsBundle\Country\Uruguay\UruguayCiDocument;
use Letkode\LatamDocumentsBundle\Country\Venezuela\VenezuelaCiDocument;
use Letkode\LatamDocumentsBundle\Country\Venezuela\VenezuelaRifDocument;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;
use Letkode\LatamDocumentsBundle\Exception\UnsupportedDocumentException;

final readonly class DocumentFactory
{
    public function make(CountryDocumentEnum $country, DocumentTypeEnum $type): DocumentInterface
    {
        return match ([$country, $type]) {
            [CountryDocumentEnum::CL, DocumentTypeEnum::RUT] => new ChileRutDocument(),
            [CountryDocumentEnum::BR, DocumentTypeEnum::CPF] => new BrazilCpfDocument(),
            [CountryDocumentEnum::BR, DocumentTypeEnum::CNPJ] => new BrazilCnpjDocument(),
            [CountryDocumentEnum::AR, DocumentTypeEnum::DNI] => new ArgentinaDniDocument(),
            [CountryDocumentEnum::AR, DocumentTypeEnum::CUIL] => new ArgentinaCuilDocument(),
            [CountryDocumentEnum::AR, DocumentTypeEnum::CUIT] => new ArgentinaCuitDocument(),
            [CountryDocumentEnum::CO, DocumentTypeEnum::CC] => new ColombiaCcDocument(),
            [CountryDocumentEnum::CO, DocumentTypeEnum::NIT] => new ColombiaNitDocument(),
            [CountryDocumentEnum::MX, DocumentTypeEnum::CURP] => new MexicoCurpDocument(),
            [CountryDocumentEnum::MX, DocumentTypeEnum::RFC] => new MexicoRfcDocument(),
            [CountryDocumentEnum::PE, DocumentTypeEnum::DNI] => new PeruDniDocument(),
            [CountryDocumentEnum::PE, DocumentTypeEnum::RUC] => new PeruRucDocument(),
            [CountryDocumentEnum::UY, DocumentTypeEnum::CI] => new UruguayCiDocument(),
            [CountryDocumentEnum::EC, DocumentTypeEnum::CI] => new EcuadorCiDocument(),
            [CountryDocumentEnum::EC, DocumentTypeEnum::RUC] => new EcuadorRucDocument(),
            [CountryDocumentEnum::DO, DocumentTypeEnum::CEDULA] => new DominicanRepublicCedulaDocument(),
            [CountryDocumentEnum::SV, DocumentTypeEnum::DUI] => new ElSalvadorDuiDocument(),
            [CountryDocumentEnum::ES, DocumentTypeEnum::NIF] => new SpainNifDocument(),
            [CountryDocumentEnum::ES, DocumentTypeEnum::NIE] => new SpainNieDocument(),
            [CountryDocumentEnum::ES, DocumentTypeEnum::CIF] => new SpainCifDocument(),
            [CountryDocumentEnum::BO, DocumentTypeEnum::CI] => new BoliviaCiDocument(),
            [CountryDocumentEnum::PY, DocumentTypeEnum::CI] => new ParaguayCiDocument(),
            [CountryDocumentEnum::VE, DocumentTypeEnum::CI] => new VenezuelaCiDocument(),
            [CountryDocumentEnum::VE, DocumentTypeEnum::RIF] => new VenezuelaRifDocument(),
            [CountryDocumentEnum::CR, DocumentTypeEnum::CI] => new CostaRicaCiDocument(),
            [CountryDocumentEnum::CR, DocumentTypeEnum::DIMEX] => new CostaRicaDimexDocument(),
            [CountryDocumentEnum::GT, DocumentTypeEnum::DPI] => new GuatemalaDpiDocument(),
            [CountryDocumentEnum::HN, DocumentTypeEnum::RNP] => new HondurasRnpDocument(),
            [CountryDocumentEnum::NI, DocumentTypeEnum::CI] => new NicaraguaCiDocument(),
            [CountryDocumentEnum::PA, DocumentTypeEnum::CI] => new PanamaCiDocument(),
            [CountryDocumentEnum::CU, DocumentTypeEnum::CI] => new CubaCiDocument(),
            default => throw new UnsupportedDocumentException($country, $type),
        };
    }

    public function supports(CountryDocumentEnum $country, DocumentTypeEnum $type): bool
    {
        try {
            $this->make($country, $type);

            return true;
        } catch (UnsupportedDocumentException) {
            return false;
        }
    }
}
