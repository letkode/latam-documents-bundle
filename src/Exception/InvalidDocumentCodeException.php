<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Exception;

final class InvalidDocumentCodeException extends DocumentException
{
    public function __construct(
        public readonly string $countryCode,
        public readonly string $documentTypeCode,
    ) {
        parent::__construct(\sprintf(
            'Unknown country/document type code combination: "%s"/"%s".',
            $countryCode,
            $documentTypeCode,
        ));
    }
}
