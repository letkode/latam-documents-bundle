<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Country\Argentina;

use Letkode\LatamDocumentsBundle\Contract\DocumentInterface;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;

final readonly class ArgentinaCuilDocument implements DocumentInterface
{
    private const array WEIGHTS = [5, 4, 3, 2, 7, 6, 5, 4, 3, 2];

    public function getCountry(): CountryDocumentEnum
    {
        return CountryDocumentEnum::AR;
    }

    public function getType(): DocumentTypeEnum
    {
        return DocumentTypeEnum::CUIL;
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

        $sum = 0;

        foreach (self::WEIGHTS as $i => $weight) {
            $sum += (int) $clean[$i] * $weight;
        }

        $remainder = $sum % 11;
        $dv = match ($remainder) {
            0 => 0,
            1 => 9,
            default => 11 - $remainder,
        };

        return (int) $clean[10] === $dv;
    }

    public function format(string $raw): string
    {
        $clean = $this->normalize($raw);

        if (\strlen($clean) < 11) {
            return $clean;
        }

        return \sprintf('%s-%s-%s', substr($clean, 0, 2), substr($clean, 2, 8), substr($clean, 10, 1));
    }
}
