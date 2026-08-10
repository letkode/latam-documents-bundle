<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Country\Colombia;

use Letkode\LatamDocumentsBundle\Contract\DocumentInterface;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;

final readonly class ColombiaNitDocument implements DocumentInterface
{
    private const array WEIGHTS = [3, 7, 13, 17, 19, 23, 29, 37, 41, 43, 47, 53, 59, 67, 71];

    public function getCountry(): CountryDocumentEnum
    {
        return CountryDocumentEnum::CO;
    }

    public function getType(): DocumentTypeEnum
    {
        return DocumentTypeEnum::NIT;
    }

    public function normalize(string $raw): string
    {
        return preg_replace('/\D/', '', mb_trim($raw)) ?? '';
    }

    public function isValid(string $raw): bool
    {
        $clean = $this->normalize($raw);
        $len = \strlen($clean);

        // With or without check digit (9 or 10 digits)
        if ($len < 9 || $len > 10) {
            return false;
        }

        $body = substr($clean, 0, 9);
        $dv = 10 === $len ? (int) $clean[9] : null;

        $sum = 0;
        $digits = array_reverse(str_split($body));

        foreach ($digits as $i => $digit) {
            $sum += (int) $digit * self::WEIGHTS[$i];
        }

        $remainder = $sum % 11;
        $expected = $remainder >= 2 ? 11 - $remainder : $remainder;

        return null === $dv || $dv === $expected;
    }

    public function format(string $raw): string
    {
        $clean = $this->normalize($raw);

        if (\strlen($clean) < 10) {
            return $clean;
        }

        return \sprintf('%s-%s', substr($clean, 0, 9), substr($clean, 9, 1));
    }
}
