<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Country\Cuba;

use Letkode\LatamDocumentsBundle\Contract\DocumentInterface;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;

final readonly class CubaCiDocument implements DocumentInterface
{
    // 11 digits: YYMMDD + 5 digits (last is check-like digit)
    // Date embedded in positions 0-5
    private const string PATTERN = '/^\d{2}(0[1-9]|1[0-2])(0[1-9]|[12]\d|3[01])\d{5}$/';

    public function getCountry(): CountryDocumentEnum
    {
        return CountryDocumentEnum::CU;
    }

    public function getType(): DocumentTypeEnum
    {
        return DocumentTypeEnum::CI;
    }

    public function normalize(string $raw): string
    {
        return preg_replace('/\D/', '', mb_trim($raw)) ?? '';
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
