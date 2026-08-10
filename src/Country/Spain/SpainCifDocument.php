<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Country\Spain;

use Letkode\LatamDocumentsBundle\Contract\DocumentInterface;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;

final readonly class SpainCifDocument implements DocumentInterface
{
    // Types that use letter as control digit
    private const string LETTER_CONTROL_TYPES = 'PQRSNW';
    private const string CONTROL_LETTERS = 'JABCDEFGHI';

    public function getCountry(): CountryDocumentEnum
    {
        return CountryDocumentEnum::ES;
    }

    public function getType(): DocumentTypeEnum
    {
        return DocumentTypeEnum::CIF;
    }

    public function normalize(string $raw): string
    {
        return strtoupper(preg_replace('/[\s\-]/', '', mb_trim($raw)) ?? '');
    }

    public function isValid(string $raw): bool
    {
        $clean = $this->normalize($raw);

        if (!preg_match('/^[ABCDEFGHJKLMNPQRSVUW]\d{7}[0-9A-J]$/', $clean)) {
            return false;
        }

        $type = $clean[0];
        $digits = substr($clean, 1, 7);
        $control = substr($clean, 8, 1);

        $sum = 0;

        for ($i = 0; $i < 7; ++$i) {
            $digit = (int) $digits[$i];

            if (0 === $i % 2) {
                $digit *= 2;
                $digit = (int) floor($digit / 10) + ($digit % 10);
            }

            $sum += $digit;
        }

        $controlDigit = (10 - ($sum % 10)) % 10;
        $controlLetter = self::CONTROL_LETTERS[$controlDigit];

        if (str_contains(self::LETTER_CONTROL_TYPES, $type)) {
            return $control === $controlLetter;
        }

        // Types that allow both digit or letter
        return $control === (string) $controlDigit || $control === $controlLetter;
    }

    public function format(string $raw): string
    {
        return $this->normalize($raw);
    }
}
