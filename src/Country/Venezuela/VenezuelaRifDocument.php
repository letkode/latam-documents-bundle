<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Country\Venezuela;

use Letkode\LatamDocumentsBundle\Contract\DocumentInterface;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;

final readonly class VenezuelaRifDocument implements DocumentInterface
{
    public function getCountry(): CountryDocumentEnum
    {
        return CountryDocumentEnum::VE;
    }

    public function getType(): DocumentTypeEnum
    {
        return DocumentTypeEnum::RIF;
    }

    public function normalize(string $raw): string
    {
        return strtoupper(preg_replace('/[\s\-]/', '', mb_trim($raw)) ?? '');
    }

    public function isValid(string $raw): bool
    {
        return 1 === preg_match('/^[JGVEP]-?\d{9}-?\d$/', strtoupper(mb_trim($raw)));
    }

    public function format(string $raw): string
    {
        $clean = $this->normalize($raw);

        if (!preg_match('/^([JGVEP])(\d{9})(\d)$/', $clean, $m)) {
            return $clean;
        }

        return \sprintf('%s-%s-%s', $m[1], $m[2], $m[3]);
    }
}
