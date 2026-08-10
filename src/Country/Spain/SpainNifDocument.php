<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Country\Spain;

use Letkode\LatamDocumentsBundle\Contract\DocumentInterface;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;

final readonly class SpainNifDocument implements DocumentInterface
{
    public const string LETTERS = 'TRWAGMYFPDXBNJZSQVHLCKE';

    public function getCountry(): CountryDocumentEnum
    {
        return CountryDocumentEnum::ES;
    }

    public function getType(): DocumentTypeEnum
    {
        return DocumentTypeEnum::NIF;
    }

    public function normalize(string $raw): string
    {
        return strtoupper(preg_replace('/[\s\-]/', '', mb_trim($raw)) ?? '');
    }

    public function isValid(string $raw): bool
    {
        $clean = $this->normalize($raw);

        if (!preg_match('/^\d{8}[A-Z]$/', $clean)) {
            return false;
        }

        $number = (int) substr($clean, 0, 8);
        $letter = substr($clean, 8, 1);

        return $letter === self::LETTERS[$number % 23];
    }

    public function format(string $raw): string
    {
        $clean = $this->normalize($raw);

        if (\strlen($clean) < 9) {
            return $clean;
        }

        return \sprintf('%s-%s', substr($clean, 0, 8), substr($clean, 8, 1));
    }
}
