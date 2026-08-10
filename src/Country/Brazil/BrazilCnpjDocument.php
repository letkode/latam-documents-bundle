<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Country\Brazil;

use Letkode\LatamDocumentsBundle\Contract\DocumentInterface;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;

final readonly class BrazilCnpjDocument implements DocumentInterface
{
    private const array WEIGHTS_1 = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
    private const array WEIGHTS_2 = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

    public function getCountry(): CountryDocumentEnum
    {
        return CountryDocumentEnum::BR;
    }

    public function getType(): DocumentTypeEnum
    {
        return DocumentTypeEnum::CNPJ;
    }

    public function normalize(string $raw): string
    {
        return preg_replace('/\D/', '', mb_trim($raw)) ?? '';
    }

    public function isValid(string $raw): bool
    {
        $clean = $this->normalize($raw);

        if (14 !== \strlen($clean)) {
            return false;
        }

        if (1 === \count(array_unique(str_split($clean)))) {
            return false;
        }

        $dv1 = $this->calcDigit($clean, self::WEIGHTS_1);
        $dv2 = $this->calcDigit($clean, self::WEIGHTS_2);

        return $clean[12] === (string) $dv1 && $clean[13] === (string) $dv2;
    }

    public function format(string $raw): string
    {
        $clean = str_pad($this->normalize($raw), 14, '0', \STR_PAD_LEFT);

        return \sprintf(
            '%s.%s.%s/%s-%s',
            substr($clean, 0, 2),
            substr($clean, 2, 3),
            substr($clean, 5, 3),
            substr($clean, 8, 4),
            substr($clean, 12, 2),
        );
    }

    /**
     * @param array<int, int> $weights
     */
    private function calcDigit(string $cnpj, array $weights): int
    {
        $sum = 0;

        foreach ($weights as $i => $weight) {
            $sum += (int) $cnpj[$i] * $weight;
        }

        $remainder = $sum % 11;

        return $remainder < 2 ? 0 : 11 - $remainder;
    }
}
