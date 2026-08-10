<?php

declare(strict_types=1);

namespace Letkode\LatamDocumentsBundle\Tests\Translations;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

final class TranslationKeysTest extends TestCase
{
    private const TRANSLATIONS_DIR = __DIR__ . '/../../src/Translations';

    public function testAllLocalesHaveSameKeys(): void
    {
        $locales = ['es', 'en', 'pt'];
        $keysByLocale = [];

        foreach ($locales as $locale) {
            $file = self::TRANSLATIONS_DIR . "/latam_documents.{$locale}.yaml";
            self::assertFileExists($file, "Missing translation file for locale: {$locale}");

            $parsed = Yaml::parseFile($file);
            if (!\is_array($parsed)) {
                self::fail("Invalid YAML structure in {$file}");
            }

            $keysByLocale[$locale] = array_keys($parsed);
            sort($keysByLocale[$locale]);
        }

        foreach ($locales as $locale) {
            self::assertSame(
                $keysByLocale['es'],
                $keysByLocale[$locale],
                "Translation keys mismatch between 'es' and '{$locale}'"
            );
        }
    }

    public function testNoEmptyTranslationValues(): void
    {
        foreach (['es', 'en', 'pt'] as $locale) {
            $data = Yaml::parseFile(self::TRANSLATIONS_DIR . "/latam_documents.{$locale}.yaml");
            if (!\is_array($data)) {
                self::fail("Invalid YAML structure for locale '{$locale}'");
            }

            foreach ($data as $key => $value) {
                self::assertNotEmpty($value, "Empty translation for key '{$key}' in locale '{$locale}'");
            }
        }
    }
}
