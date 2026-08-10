<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Country\Nicaragua;

use Letkode\LatamDocumentsBundle\Contract\DocumentInterface;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;

final readonly class NicaraguaCiDocument implements DocumentInterface
{
    // Format: DDD-DDDDDD-DDDDL (3 digits + 6 digits + 4 digits + 1 letter)
    private const string PATTERN = '/^\d{3}-?\d{6}-?\d{4}[A-Z]$/';

    public function getCountry(): CountryDocumentEnum
    {
        return CountryDocumentEnum::NI;
    }

    public function getType(): DocumentTypeEnum
    {
        return DocumentTypeEnum::CI;
    }

    public function normalize(string $raw): string
    {
        return strtoupper(preg_replace('/[\s\-]/', '', mb_trim($raw)) ?? '');
    }

    public function isValid(string $raw): bool
    {
        return 1 === preg_match(self::PATTERN, strtoupper(mb_trim($raw)));
    }

    public function format(string $raw): string
    {
        $clean = $this->normalize($raw);

        if (\strlen($clean) < 14) {
            return $clean;
        }

        return \sprintf('%s-%s-%s', substr($clean, 0, 3), substr($clean, 3, 6), substr($clean, 9));
    }
}
