<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Country\Mexico;

use Letkode\LatamDocumentsBundle\Contract\DocumentInterface;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;

final readonly class MexicoRfcDocument implements DocumentInterface
{
    // Persona física: 4 letras + 6 dígitos fecha + 3 homoclave
    private const string PATTERN_PERSON = '/^[A-Z&Ñ]{4}\d{6}[A-Z0-9]{3}$/';
    // Persona moral: 3 letras + 6 dígitos fecha + 3 homoclave
    private const string PATTERN_COMPANY = '/^[A-Z&Ñ]{3}\d{6}[A-Z0-9]{3}$/';

    public function getCountry(): CountryDocumentEnum
    {
        return CountryDocumentEnum::MX;
    }

    public function getType(): DocumentTypeEnum
    {
        return DocumentTypeEnum::RFC;
    }

    public function normalize(string $raw): string
    {
        return strtoupper(preg_replace('/[\s\-]/', '', mb_trim($raw)) ?? '');
    }

    public function isValid(string $raw): bool
    {
        $clean = $this->normalize($raw);

        if (1 === preg_match(self::PATTERN_PERSON, $clean)) {
            return $this->isValidDate(substr($clean, 4, 6));
        }

        if (1 === preg_match(self::PATTERN_COMPANY, $clean)) {
            return $this->isValidDate(substr($clean, 3, 6));
        }

        return false;
    }

    private function isValidDate(string $yymmdd): bool
    {
        $mm = (int) substr($yymmdd, 2, 2);
        $dd = (int) substr($yymmdd, 4, 2);

        return $mm >= 1 && $mm <= 12 && $dd >= 1 && $dd <= 31;
    }

    public function format(string $raw): string
    {
        return $this->normalize($raw);
    }
}
