<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Country\Guatemala;

use Letkode\LatamDocumentsBundle\Contract\DocumentInterface;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;

final readonly class GuatemalaDpiDocument implements DocumentInterface
{
    public function getCountry(): CountryDocumentEnum
    {
        return CountryDocumentEnum::GT;
    }

    public function getType(): DocumentTypeEnum
    {
        return DocumentTypeEnum::DPI;
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

        return \sprintf('%s %s %s', substr($clean, 0, 4), substr($clean, 4, 5), substr($clean, 9, 4));
    }
}
