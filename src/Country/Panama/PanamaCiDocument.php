<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Country\Panama;

use Letkode\LatamDocumentsBundle\Contract\DocumentInterface;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;

final readonly class PanamaCiDocument implements DocumentInterface
{
    // Formats: N-NNN-NNNN | NN-NNN-NNNN | NNN-NNN-NNNN | PE-NNN-NNNN | E-NNN-NNNN | N-AV-NNNN
    private const string PATTERN = '/^(\d{1,3}|[A-Z]{1,2}([-]?\d{1,2})?)[-]?\d{3}[-]?\d{4}$/';

    public function getCountry(): CountryDocumentEnum
    {
        return CountryDocumentEnum::PA;
    }

    public function getType(): DocumentTypeEnum
    {
        return DocumentTypeEnum::CI;
    }

    public function normalize(string $raw): string
    {
        return strtoupper(preg_replace('/\s/', '', mb_trim($raw)) ?? '');
    }

    public function isValid(string $raw): bool
    {
        return 1 === preg_match(self::PATTERN, $this->normalize($raw));
    }

    public function format(string $raw): string
    {
        // Keep existing separators or return normalized
        return $this->normalize($raw);
    }
}
