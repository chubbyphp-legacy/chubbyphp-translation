<?php

namespace Chubbyphp\Tests\Translation;

use Chubbyphp\Translation\LocaleTranslationProvider;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Translation\LocaleTranslationProvider
 */
final class LocaleTranslationProviderTest extends TestCase
{
    public function testGetLocale()
    {
        $expectedLocale = 'en';

        $provider = new LocaleTranslationProvider($expectedLocale, []);

        self::assertSame($expectedLocale, $provider->getLocale());
    }

    public function testTranslateWithoutArguments()
    {
        $expectedLocale = 'en';
        $expectedTranslations = [
            'some.existing.key' => 'successful translation',
        ];

        $provider = new LocaleTranslationProvider($expectedLocale, $expectedTranslations);

        self::assertSame($expectedTranslations['some.existing.key'], $provider->translate('some.existing.key'));
        self::assertNull($provider->translate('some.not.existing.key'));
    }

    public function testTranslateWithoutNamedArguments()
    {
        $expectedLocale = 'en';
        $expectedTranslations = [
            'some.existing.key' => '%d successful translations',
        ];

        $provider = new LocaleTranslationProvider($expectedLocale, $expectedTranslations);

        self::assertSame('5 successful translations', $provider->translate('some.existing.key', [5]));
        self::assertNull($provider->translate('some.not.existing.key', [5]));
    }

    public function testTranslateWithNamedArguments()
    {
        $expectedLocale = 'en';
        $expectedTranslations = [
            'some.existing.key' => '{{key}} successful translations',
        ];

        $provider = new LocaleTranslationProvider($expectedLocale, $expectedTranslations);

        self::assertSame('5 successful translations', $provider->translate('some.existing.key', ['key' => 5]));
        self::assertNull($provider->translate('some.not.existing.key', ['key' => 5]));
    }
}
