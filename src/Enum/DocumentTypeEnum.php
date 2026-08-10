<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Enum;

enum DocumentTypeEnum: string
{
    case RUT = 'RUT';    // Chile
    case CPF = 'CPF';    // Brasil — persona natural
    case CNPJ = 'CNPJ';  // Brasil — empresa
    case DNI = 'DNI';    // Argentina / Perú
    case CUIL = 'CUIL';  // Argentina — persona
    case CUIT = 'CUIT';  // Argentina — empresa
    case CURP = 'CURP';  // México — persona
    case RFC = 'RFC';    // México — empresa/persona
    case CC = 'CC';     // Colombia — cédula ciudadanía
    case NIT = 'NIT';    // Colombia — empresa
    case RUC = 'RUC';    // Perú / Ecuador — empresa
    case CI = 'CI';     // Uruguay / Ecuador / Bolivia / Paraguay / Venezuela / Costa Rica / Nicaragua / Cuba
    case RIF = 'RIF';    // Venezuela — empresa
    case DUI = 'DUI';    // El Salvador
    case DPI = 'DPI';    // Guatemala
    case RNP = 'RNP';    // Honduras
    case CEDULA = 'CEDULA'; // República Dominicana
    case DIMEX = 'DIMEX';  // Costa Rica — extranjero
    case NIF = 'NIF';    // España — persona
    case NIE = 'NIE';    // España — extranjero
    case CIF = 'CIF';    // España — empresa
}
