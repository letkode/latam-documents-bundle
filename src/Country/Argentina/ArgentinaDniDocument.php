<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Country\Argentina;

use Letkode\LatamDocumentsBundle\Contract\DocumentInterface;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;

final readonly class ArgentinaDniDocument implements DocumentInterface
{
    public function getCountry(): CountryDocumentEnum
    {
        return CountryDocumentEnum::AR;
    }

    public function getType(): DocumentTypeEnum
    {
        return DocumentTypeEnum::DNI;
    }

    public function normalize(string $raw): string
    {
        return preg_replace('/\D/', '', mb_trim($raw)) ?? '';
    }

    public function isValid(string $raw): bool
    {
        $clean = $this->normalize($raw);

        return 1 === preg_match('/^\d{7,8}$/', $clean);
    }

    public function format(string $raw): string
    {
        $clean = $this->normalize($raw);
        $padded = str_pad($clean, 8, '0', \STR_PAD_LEFT);

        return \sprintf(
            '%s.%s.%s',
            substr($padded, 0, 2),
            substr($padded, 2, 3),
            substr($padded, 5, 3),
        );
    }
}
