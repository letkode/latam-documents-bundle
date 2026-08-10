<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Country\CostaRica;

use Letkode\LatamDocumentsBundle\Contract\DocumentInterface;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;

final readonly class CostaRicaCiDocument implements DocumentInterface
{
    public function getCountry(): CountryDocumentEnum
    {
        return CountryDocumentEnum::CR;
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
        return 1 === preg_match('/^\d{9}$/', $this->normalize($raw));
    }

    public function format(string $raw): string
    {
        $clean = str_pad($this->normalize($raw), 9, '0', \STR_PAD_LEFT);

        return \sprintf('%s-%s-%s', substr($clean, 0, 1), substr($clean, 1, 4), substr($clean, 5, 4));
    }
}
