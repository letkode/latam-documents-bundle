<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Country\Colombia;

use Letkode\LatamDocumentsBundle\Contract\DocumentInterface;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;

final readonly class ColombiaCcDocument implements DocumentInterface
{
    public function getCountry(): CountryDocumentEnum
    {
        return CountryDocumentEnum::CO;
    }

    public function getType(): DocumentTypeEnum
    {
        return DocumentTypeEnum::CC;
    }

    public function normalize(string $raw): string
    {
        return preg_replace('/\D/', '', mb_trim($raw)) ?? '';
    }

    public function isValid(string $raw): bool
    {
        $clean = $this->normalize($raw);

        return 1 === preg_match('/^\d{6,10}$/', $clean);
    }

    public function format(string $raw): string
    {
        return $this->normalize($raw);
    }
}
