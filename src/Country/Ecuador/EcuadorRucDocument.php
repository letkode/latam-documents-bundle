<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Country\Ecuador;

use Letkode\LatamDocumentsBundle\Contract\DocumentInterface;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;

final readonly class EcuadorRucDocument implements DocumentInterface
{
    private EcuadorCiDocument $ciValidator;

    public function __construct()
    {
        $this->ciValidator = new EcuadorCiDocument();
    }

    public function getCountry(): CountryDocumentEnum
    {
        return CountryDocumentEnum::EC;
    }

    public function getType(): DocumentTypeEnum
    {
        return DocumentTypeEnum::RUC;
    }

    public function normalize(string $raw): string
    {
        return preg_replace('/\D/', '', mb_trim($raw)) ?? '';
    }

    public function isValid(string $raw): bool
    {
        $clean = $this->normalize($raw);

        if (13 !== \strlen($clean)) {
            return false;
        }

        $suffix = substr($clean, 10, 3);

        // Suffix 001 for natural persons, 000 invalid
        if ('000' === $suffix) {
            return false;
        }

        // First 10 digits must be a valid CI
        return $this->ciValidator->isValid(substr($clean, 0, 10));
    }

    public function format(string $raw): string
    {
        return $this->normalize($raw);
    }
}
