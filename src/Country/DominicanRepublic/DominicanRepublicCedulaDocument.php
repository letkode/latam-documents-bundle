<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Country\DominicanRepublic;

use Letkode\LatamDocumentsBundle\Contract\DocumentInterface;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;

final readonly class DominicanRepublicCedulaDocument implements DocumentInterface
{
    public function getCountry(): CountryDocumentEnum
    {
        return CountryDocumentEnum::DO;
    }

    public function getType(): DocumentTypeEnum
    {
        return DocumentTypeEnum::CEDULA;
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

        // Luhn mod 10
        $sum = 0;

        for ($i = 0; $i < 10; ++$i) {
            $digit = (int) $clean[$i];

            if (0 !== $i % 2) {
                $digit *= 2;

                if ($digit > 9) {
                    $digit -= 9;
                }
            }

            $sum += $digit;
        }

        $dv = (10 - ($sum % 10)) % 10;

        return (int) $clean[10] === $dv;
    }

    public function format(string $raw): string
    {
        $clean = $this->normalize($raw);

        if (\strlen($clean) < 11) {
            return $clean;
        }

        return \sprintf('%s-%s-%s', substr($clean, 0, 3), substr($clean, 3, 7), substr($clean, 10, 1));
    }
}
