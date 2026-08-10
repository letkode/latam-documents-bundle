<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Country\Spain;

use Letkode\LatamDocumentsBundle\Contract\DocumentInterface;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;

final readonly class SpainNieDocument implements DocumentInterface
{
    private const array PREFIX_MAP = ['X' => '0', 'Y' => '1', 'Z' => '2'];

    public function getCountry(): CountryDocumentEnum
    {
        return CountryDocumentEnum::ES;
    }

    public function getType(): DocumentTypeEnum
    {
        return DocumentTypeEnum::NIE;
    }

    public function normalize(string $raw): string
    {
        return strtoupper(preg_replace('/[\s\-]/', '', mb_trim($raw)) ?? '');
    }

    public function isValid(string $raw): bool
    {
        $clean = $this->normalize($raw);

        if (!preg_match('/^[XYZ]\d{7}[A-Z]$/', $clean)) {
            return false;
        }

        $converted = self::PREFIX_MAP[$clean[0]] . substr($clean, 1, 7);
        $number = (int) $converted;
        $letter = substr($clean, 8, 1);

        return $letter === SpainNifDocument::LETTERS[$number % 23];
    }

    public function format(string $raw): string
    {
        $clean = $this->normalize($raw);

        if (\strlen($clean) < 9) {
            return $clean;
        }

        return \sprintf('%s-%s-%s', substr($clean, 0, 1), substr($clean, 1, 7), substr($clean, 8, 1));
    }
}
