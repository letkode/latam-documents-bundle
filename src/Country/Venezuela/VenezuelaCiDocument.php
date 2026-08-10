<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Country\Venezuela;

use Letkode\LatamDocumentsBundle\Contract\DocumentInterface;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;

final readonly class VenezuelaCiDocument implements DocumentInterface
{
    public function getCountry(): CountryDocumentEnum
    {
        return CountryDocumentEnum::VE;
    }

    public function getType(): DocumentTypeEnum
    {
        return DocumentTypeEnum::CI;
    }

    public function normalize(string $raw): string
    {
        $clean = strtoupper(mb_trim($raw));

        // Remove prefix V- or E- if present
        return preg_replace('/^[VE]-?/', '', $clean) ?? '';
    }

    public function isValid(string $raw): bool
    {
        $clean = strtoupper(mb_trim($raw));

        return 1 === preg_match('/^[VE]-?\d{6,8}$/', $clean);
    }

    public function format(string $raw): string
    {
        $clean = strtoupper(mb_trim($raw));
        preg_match('/^([VE])-?(\d{6,8})$/', $clean, $matches);

        if (!isset($matches[1], $matches[2])) {
            return $this->normalize($raw);
        }

        return \sprintf('%s-%s', $matches[1], $matches[2]);
    }
}
