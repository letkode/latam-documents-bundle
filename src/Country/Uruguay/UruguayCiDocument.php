<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Country\Uruguay;

use Letkode\LatamDocumentsBundle\Contract\DocumentInterface;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;

final readonly class UruguayCiDocument implements DocumentInterface
{
    private const array WEIGHTS = [2, 9, 8, 7, 6, 3, 4];

    public function getCountry(): CountryDocumentEnum
    {
        return CountryDocumentEnum::UY;
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
        $clean = str_pad($this->normalize($raw), 8, '0', \STR_PAD_LEFT);

        if (8 !== \strlen($clean)) {
            return false;
        }

        $sum = 0;

        foreach (self::WEIGHTS as $i => $weight) {
            $sum += (int) $clean[$i] * $weight;
        }

        $dv = $sum % 10;
        $dv = 0 === $dv ? 0 : 10 - $dv;

        return (int) $clean[7] === $dv;
    }

    public function format(string $raw): string
    {
        $clean = str_pad($this->normalize($raw), 8, '0', \STR_PAD_LEFT);

        return \sprintf(
            '%s.%s.%s-%s',
            ltrim(substr($clean, 0, 1), '0') ?: '0',
            substr($clean, 1, 3),
            substr($clean, 4, 3),
            substr($clean, 7, 1),
        );
    }
}
