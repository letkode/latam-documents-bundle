<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Country\Brazil;

use Letkode\LatamDocumentsBundle\Contract\DocumentInterface;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;

final readonly class BrazilCpfDocument implements DocumentInterface
{
    public function getCountry(): CountryDocumentEnum
    {
        return CountryDocumentEnum::BR;
    }

    public function getType(): DocumentTypeEnum
    {
        return DocumentTypeEnum::CPF;
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

        // Rejects sequences like 00000000000
        if (1 === \count(array_unique(str_split($clean)))) {
            return false;
        }

        $dv1 = $this->calcDigit($clean, 10);
        $dv2 = $this->calcDigit($clean, 11);

        return $clean[9] === (string) $dv1 && $clean[10] === (string) $dv2;
    }

    public function format(string $raw): string
    {
        $clean = str_pad($this->normalize($raw), 11, '0', \STR_PAD_LEFT);

        return \sprintf(
            '%s.%s.%s-%s',
            substr($clean, 0, 3),
            substr($clean, 3, 3),
            substr($clean, 6, 3),
            substr($clean, 9, 2),
        );
    }

    private function calcDigit(string $cpf, int $weight): int
    {
        $sum = 0;

        for ($i = 0; $i < $weight - 1; ++$i) {
            $sum += (int) $cpf[$i] * ($weight - $i);
        }

        $remainder = $sum % 11;

        return $remainder < 2 ? 0 : 11 - $remainder;
    }
}
