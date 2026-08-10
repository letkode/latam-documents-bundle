<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Country\Honduras;

use Letkode\LatamDocumentsBundle\Contract\DocumentInterface;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;

final readonly class HondurasRnpDocument implements DocumentInterface
{
    public function getCountry(): CountryDocumentEnum
    {
        return CountryDocumentEnum::HN;
    }

    public function getType(): DocumentTypeEnum
    {
        return DocumentTypeEnum::RNP;
    }

    public function normalize(string $raw): string
    {
        return preg_replace('/\D/', '', mb_trim($raw)) ?? '';
    }

    public function isValid(string $raw): bool
    {
        return 1 === preg_match('/^\d{13}$/', $this->normalize($raw));
    }

    public function format(string $raw): string
    {
        $clean = str_pad($this->normalize($raw), 13, '0', \STR_PAD_LEFT);

        // DDDD-YYYY-XXXXX
        return \sprintf('%s-%s-%s', substr($clean, 0, 4), substr($clean, 4, 4), substr($clean, 8, 5));
    }
}
