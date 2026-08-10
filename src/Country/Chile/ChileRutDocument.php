<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Country\Chile;

use Letkode\LatamDocumentsBundle\Contract\DocumentInterface;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;

final readonly class ChileRutDocument implements DocumentInterface
{
    public function getCountry(): CountryDocumentEnum
    {
        return CountryDocumentEnum::CL;
    }

    public function getType(): DocumentTypeEnum
    {
        return DocumentTypeEnum::RUT;
    }

    public function normalize(string $raw): string
    {
        return strtoupper(preg_replace('/[.\-\s]/', '', mb_trim($raw)) ?? '');
    }

    public function isValid(string $raw): bool
    {
        $clean = mb_trim($raw);

        if (!preg_match('/^[0-9.]+[-]?[0-9kK]{1}$/', $clean)) {
            return false;
        }

        $clean = preg_replace('/[.\-]/i', '', $clean) ?? '';
        $rutNumber = substr($clean, 0, \strlen($clean) - 1);
        $rutDv = substr($clean, -1);

        if (!is_numeric($rutNumber)) {
            return false;
        }

        $index = 2;
        $sum = 0;

        foreach (array_reverse(str_split($rutNumber)) as $digit) {
            if (8 === $index) {
                $index = 2;
            }
            $sum += (int) $digit * $index;
            ++$index;
        }

        $dv = 11 - ($sum % 11);

        if (11 === $dv) {
            $dv = 0;
        }

        if (10 === $dv) {
            $dv = 'K';
        }

        return (string) $dv === strtoupper($rutDv);
    }

    public function format(string $raw): string
    {
        $clean = $this->normalize($raw);

        if (\strlen($clean) < 2) {
            return $clean;
        }

        $body = substr($clean, 0, -1);
        $dv = substr($clean, -1);
        $body = number_format((int) $body, 0, '', '.');

        return \sprintf('%s-%s', $body, $dv);
    }
}
