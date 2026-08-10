<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Country\ElSalvador;

use Letkode\LatamDocumentsBundle\Contract\DocumentInterface;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;

final readonly class ElSalvadorDuiDocument implements DocumentInterface
{
    public function getCountry(): CountryDocumentEnum
    {
        return CountryDocumentEnum::SV;
    }

    public function getType(): DocumentTypeEnum
    {
        return DocumentTypeEnum::DUI;
    }

    public function normalize(string $raw): string
    {
        return preg_replace('/\D/', '', mb_trim($raw)) ?? '';
    }

    public function isValid(string $raw): bool
    {
        $clean = $this->normalize($raw);

        if (9 !== \strlen($clean)) {
            return false;
        }

        $sum = 0;

        for ($i = 0; $i < 8; ++$i) {
            $sum += (int) $clean[$i] * (8 - $i);
        }

        $dv = $sum % 10;
        $dv = 0 === $dv ? 0 : 10 - $dv;

        return (int) $clean[8] === $dv;
    }

    public function format(string $raw): string
    {
        $clean = $this->normalize($raw);

        if (\strlen($clean) < 9) {
            return $clean;
        }

        return \sprintf('%s-%s', substr($clean, 0, 8), substr($clean, 8, 1));
    }
}
