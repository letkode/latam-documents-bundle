<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Country\Ecuador;

use Letkode\LatamDocumentsBundle\Contract\DocumentInterface;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;

final readonly class EcuadorCiDocument implements DocumentInterface
{
    public function getCountry(): CountryDocumentEnum
    {
        return CountryDocumentEnum::EC;
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
        $clean = $this->normalize($raw);

        if (10 !== \strlen($clean)) {
            return false;
        }

        // Province code 01-24
        $province = (int) substr($clean, 0, 2);

        if ($province < 1 || $province > 24) {
            return false;
        }

        return $this->checkMod10($clean);
    }

    public function format(string $raw): string
    {
        return $this->normalize($raw);
    }

    private function checkMod10(string $clean): bool
    {
        $sum = 0;

        for ($i = 0; $i < 9; ++$i) {
            $digit = (int) $clean[$i];

            if (0 === $i % 2) {
                $digit *= 2;

                if ($digit >= 10) {
                    $digit -= 9;
                }
            }

            $sum += $digit;
        }

        $dv = 0 === $sum % 10 ? 0 : 10 - ($sum % 10);

        return (int) $clean[9] === $dv;
    }
}
