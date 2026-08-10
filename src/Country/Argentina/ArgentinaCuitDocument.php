<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Country\Argentina;

use Letkode\LatamDocumentsBundle\Contract\DocumentInterface;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;

/**
 * CUIT shares the same Módulo 11 algorithm as CUIL.
 * Prefix differs: 20/23/24/27 (persona) vs 30/33/34 (empresa).
 */
final readonly class ArgentinaCuitDocument implements DocumentInterface
{
    private ArgentinaCuilDocument $delegate;

    public function __construct()
    {
        $this->delegate = new ArgentinaCuilDocument();
    }

    public function getCountry(): CountryDocumentEnum
    {
        return CountryDocumentEnum::AR;
    }

    public function getType(): DocumentTypeEnum
    {
        return DocumentTypeEnum::CUIT;
    }

    public function normalize(string $raw): string
    {
        return $this->delegate->normalize($raw);
    }

    public function isValid(string $raw): bool
    {
        return $this->delegate->isValid($raw);
    }

    public function format(string $raw): string
    {
        return $this->delegate->format($raw);
    }
}
