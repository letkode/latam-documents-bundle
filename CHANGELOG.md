# Changelog

All notable changes to `letkode/latam-documents-bundle` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.1.0] - 2026-09-05

### Added
- `DocumentProcessor::processByCode()` convenience entry point for callers that
  hold country and document type as plain strings (e.g. loaded from a database)
  instead of the bundle's enums. Accepts `null` raw input and short-circuits to
  `null` for optional fields.
- `InvalidDocumentCodeException` thrown when the provided country or document
  type code does not match `CountryDocumentEnum`/`DocumentTypeEnum`; carries the
  offending `countryCode` and `documentTypeCode`.

## [1.0.0] - 2026-05-03

### Added
- Initial release of the `letkode/latam-documents-bundle` library.
- Document validation, normalization, and formatting for Latin America and Spain.
- Support for: DNI, RUT, CPF, CNPJ, CURP, RFC, CI, RUC, NIF, NIE, CIF and more.
- `DocumentFactory` for creating document instances by country and type.
- `DocumentProcessor` for processing and validating document data.
- Symfony Translation integration via `TranslationContracts`.
- Symfony Bundle support for seamless framework integration.
- PHP 8.4+ support.

[Unreleased]: https://github.com/letkode/latam-documents-bundle/compare/1.1.0...HEAD
[1.1.0]: https://github.com/letkode/latam-documents-bundle/compare/1.0.0...1.1.0
[1.0.0]: https://github.com/letkode/latam-documents-bundle/releases/tag/1.0.0
