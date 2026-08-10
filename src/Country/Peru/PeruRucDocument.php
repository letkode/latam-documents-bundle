<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Country\Peru;

use Letkode\LatamDocumentsBundle\Contract\DocumentInterface;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;

final readonly class PeruRucDocument implements DocumentInterface
{
    private const array WEIGHTS = [5, 4, 3, 2, 7, 6, 5, 4, 3, 2];

    public function getCountry(): CountryDocumentEnum
    {
        return CountryDocumentEnum::PE;
    }

    public function getType(): DocumentTypeEnum
    {
        return DocumentTypeEnum::RUC;
    }

    public function normalize(string $raw): string
    {
        return preg_replace('/\D/', '', mb_trim($raw)) ?? '';
    }

    public function isValid(string $raw): bool
    {
        $clean = $this->normalize($raw);

        if (11 !== \strlen($clean)) {
            return false;
        }

        // Valid prefixes: 10 (natural), 15, 16, 17, 20 (company)
        $prefix = (int) substr($clean, 0, 2);

        if (!\in_array($prefix, [10, 15, 16, 17, 20], true)) {
            return false;
        }

        $sum = 0;

        foreach (self::WEIGHTS as $i => $weight) {
            $sum += (int) $clean[$i] * $weight;
        }

        $remainder = $sum % 11;
        $dv = 11 - $remainder;

        if ($dv >= 10) {
            $dv -= 10;
        }

        return (int) $clean[10] === $dv;
    }

    public function format(string $raw): string
    {
        return $this->normalize($raw);
    }
}
