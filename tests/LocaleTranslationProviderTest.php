<?php

namespace Chubbyphp\Tests\Translation;

use Chubbyphp\Translation\LocaleTranslationProvider;

/**
 * @covers Chubbyphp\Translation\LocaleTranslationProvider
 */
final class LocaleTranslationProviderTest extends \PHPUnit_Framework_TestCase
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
        self::assertSame('some.not.existing.key', $provider->translate('some.not.existing.key'));
    }

    public function testTranslateWithArguments()
    {
        $expectedLocale = 'en';
        $expectedTranslations = [
            'some.existing.key' => '%d successful translations',
        ];

        $provider = new LocaleTranslationProvider($expectedLocale, $expectedTranslations);

        self::assertSame('5 successful translations', $provider->translate('some.existing.key', [5]));
        self::assertSame('some.not.existing.key', $provider->translate('some.not.existing.key', [5]));
    }
}
