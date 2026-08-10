<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Country\Mexico;

use Letkode\LatamDocumentsBundle\Contract\DocumentInterface;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;

final readonly class MexicoCurpDocument implements DocumentInterface
{
    private const string PATTERN = '/^[A-Z][AEIOU][A-Z]{2}\d{2}(0[1-9]|1[0-2])(0[1-9]|[12]\d|3[01])[HM](AS|BC|BS|CC|CL|CM|CS|CH|DF|DG|GT|GR|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TS|TL|VZ|YN|ZS|NE)[B-DF-HJ-NP-TV-Z]{3}[0-9A-Z]\d$/';

    public function getCountry(): CountryDocumentEnum
    {
        return CountryDocumentEnum::MX;
    }

    public function getType(): DocumentTypeEnum
    {
        return DocumentTypeEnum::CURP;
    }

    public function normalize(string $raw): string
    {
        return strtoupper(preg_replace('/\s/', '', mb_trim($raw)) ?? '');
    }

    public function isValid(string $raw): bool
    {
        return 1 === preg_match(self::PATTERN, $this->normalize($raw));
    }

    public function format(string $raw): string
    {
        return $this->normalize($raw);
    }
}
