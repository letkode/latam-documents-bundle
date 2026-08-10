# Document Helpers

Validación y formateo de documentos de identidad para LATAM + España.

## Entry point

```php
$processor = new DocumentProcessor();
$result = $processor->process('12.345.678-9', CountryDocumentEnum::CL, DocumentTypeEnum::RUT);

// $result->valid     → true
// $result->formatted → '12.345.678-9'
// $result->normalized → '123456789'
```

## Países y tipos soportados

| País | Enum | Tipo | Algoritmo |
|---|---|---|---|
| Chile | `CL` | `RUT` | Módulo 11 |
| Brasil | `BR` | `CPF` / `CNPJ` | Módulo 11 doble |
| Argentina | `AR` | `DNI` / `CUIL` / `CUIT` | Módulo 11 / Regex |
| Colombia | `CO` | `CC` / `NIT` | Módulo 11 / Regex |
| México | `MX` | `CURP` / `RFC` | Regex estructurado |
| Perú | `PE` | `DNI` / `RUC` | Módulo 11 / Regex |
| Uruguay | `UY` | `CI` | Módulo 10 |
| Ecuador | `EC` | `CI` / `RUC` | Módulo 10 |
| Rep. Dom. | `DO` | `CEDULA` | Módulo 10 (Luhn) |
| El Salvador | `SV` | `DUI` | Módulo 10 |
| España | `ES` | `NIF` / `NIE` / `CIF` | Tabla mod 23 / CIF |
| Bolivia | `BO` | `CI` | Regex |
| Paraguay | `PY` | `CI` | Regex |
| Venezuela | `VE` | `CI` / `RIF` | Regex + prefijo |
| Costa Rica | `CR` | `CI` / `DIMEX` | Regex |
| Guatemala | `GT` | `DPI` | Regex |
| Honduras | `HN` | `RNP` | Regex |
| Nicaragua | `NI` | `CI` | Regex estructurado |
| Panamá | `PA` | `CI` | Regex estructurado |
| Cuba | `CU` | `CI` | Regex + fecha embebida |

## DocumentResultDTO

```php
$result->country    // CountryDocumentEnum
$result->type       // DocumentTypeEnum
$result->raw        // string — valor original
$result->normalized // string — sin separadores
$result->formatted  // string — formato canónico del país
$result->valid      // bool
```

## Uso directo de un documento

```php
$doc = (new DocumentFactory())->make(CountryDocumentEnum::BR, DocumentTypeEnum::CPF);
$doc->isValid('123.456.789-09');  // bool
$doc->format('12345678909');      // '123.456.789-09'
$doc->normalize('123.456.789-09'); // '12345678909'
```
