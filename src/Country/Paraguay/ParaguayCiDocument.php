<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Country\Paraguay;

use Letkode\LatamDocumentsBundle\Contract\DocumentInterface;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;

final readonly class ParaguayCiDocument implements DocumentInterface
{
    public function getCountry(): CountryDocumentEnum
    {
        return CountryDocumentEnum::PY;
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
        return 1 === preg_match('/^\d{6,8}$/', $this->normalize($raw));
    }

    public function format(string $raw): string
    {
        return $this->normalize($raw);
    }
}
