<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Exception;

use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;

final class UnsupportedDocumentException extends DocumentException
{
    public function __construct(CountryDocumentEnum $country, DocumentTypeEnum $type)
    {
        parent::__construct(\sprintf(
            'No document handler for %s/%s',
            $country->value,
            $type->value,
        ));
    }
}
