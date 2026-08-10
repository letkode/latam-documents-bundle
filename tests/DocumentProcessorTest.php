<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Tests;

use Letkode\LatamDocumentsBundle\DocumentProcessor;
use Letkode\LatamDocumentsBundle\Enum\CountryDocumentEnum;
use Letkode\LatamDocumentsBundle\Enum\DocumentTypeEnum;
use Letkode\LatamDocumentsBundle\Exception\InvalidDocumentException;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

final class DocumentProcessorTest extends TestCase
{
    public function testProcessReturnsValidDTO(): void
    {
        $processor = new DocumentProcessor();
        $result = $processor->process('12.345.678-5', CountryDocumentEnum::CL, DocumentTypeEnum::RUT);

        self::assertTrue($result->valid);
        self::assertNull($result->message);
        self::assertSame('123456785', $result->normalized);
        self::assertSame('12.345.678-5', $result->formatted);
    }

    public function testProcessReturnsInvalidDTOWithMessage(): void
    {
        $processor = new DocumentProcessor();
        $result = $processor->process('11111111', CountryDocumentEnum::CL, DocumentTypeEnum::RUT);

        self::assertFalse($result->valid);
        self::assertNotNull($result->message);
    }

    public function testProcessOrFailThrowsOnInvalidDocument(): void
    {
        $this->expectException(InvalidDocumentException::class);
        new DocumentProcessor()->processOrFail('11111111', CountryDocumentEnum::CL, DocumentTypeEnum::RUT);
    }

    public function testProcessOrFailReturnsResultOnValidDocument(): void
    {
        $result = new DocumentProcessor()->processOrFail('12.345.678-5', CountryDocumentEnum::CL, DocumentTypeEnum::RUT);
        self::assertTrue($result->valid);
    }

    public function testProcessUsesTranslatorForInvalidMessage(): void
    {
        $translator = $this->createMock(TranslatorInterface::class);
        $translator->expects(self::once())
            ->method('trans')
            ->with('document.invalid', self::anything(), 'latam_documents')
            ->willReturn('Translated message');

        $processor = new DocumentProcessor(translator: $translator);
        $result = $processor->process('11111111', CountryDocumentEnum::CL, DocumentTypeEnum::RUT);

        self::assertSame('Translated message', $result->message);
    }

    public function testProcessWithoutTranslatorFallsBackToDefaultMessage(): void
    {
        $processor = new DocumentProcessor();
        $result = $processor->process('11111111', CountryDocumentEnum::CL, DocumentTypeEnum::RUT);

        self::assertNotNull($result->message);
        self::assertStringContainsString('11111111', $result->message);
    }

    public function testInvalidDocumentExceptionCarriesMetadata(): void
    {
        try {
            new DocumentProcessor()->processOrFail('11111111', CountryDocumentEnum::CL, DocumentTypeEnum::RUT);
            self::fail('Expected InvalidDocumentException');
        } catch (InvalidDocumentException $e) {
            self::assertSame('11111111', $e->raw);
            self::assertSame(CountryDocumentEnum::CL, $e->country);
            self::assertSame(DocumentTypeEnum::RUT, $e->type);
        }
    }
}
