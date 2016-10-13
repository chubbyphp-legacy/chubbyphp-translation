<?php

namespace Chubbyphp\Tests\Translation;

use Chubbyphp\Translation\LocaleTranslationProvider;

/**
 * @covers Chubbyphp\Translation\LocaleTranslationProvider
 */
final class LocaleTranslationProviderTest extends \PHPUnit_Framework_TestCase
{
    use LoggerTestTrait;

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

        $logger = $this->getLogger();

        $provider = new LocaleTranslationProvider($expectedLocale, $expectedTranslations, $logger);

        self::assertSame($expectedTranslations['some.existing.key'], $provider->translate('some.existing.key'));
        self::assertSame('some.not.existing.key', $provider->translate('some.not.existing.key'));

        self::assertCount(2, $logger->__logs);
        self::assertSame('info', $logger->__logs[0]['level']);
        self::assertSame('translation: translate {locale} {key}', $logger->__logs[0]['message']);
        self::assertSame(['locale' => 'en', 'key' => 'some.existing.key'], $logger->__logs[0]['context']);
        self::assertSame('warning', $logger->__logs[1]['level']);
        self::assertSame('translation: missing {locale} {key}', $logger->__logs[1]['message']);
        self::assertSame(['locale' => 'en', 'key' => 'some.not.existing.key'], $logger->__logs[1]['context']);
    }

    public function testTranslateWithArguments()
    {
        $expectedLocale = 'en';
        $expectedTranslations = [
            'some.existing.key' => '%d successful translations',
        ];

        $logger = $this->getLogger();

        $provider = new LocaleTranslationProvider($expectedLocale, $expectedTranslations, $logger);

        self::assertSame('5 successful translations', $provider->translate('some.existing.key', [5]));
        self::assertSame('some.not.existing.key', $provider->translate('some.not.existing.key', [5]));

        self::assertCount(2, $logger->__logs);
        self::assertSame('info', $logger->__logs[0]['level']);
        self::assertSame('translation: translate {locale} {key}', $logger->__logs[0]['message']);
        self::assertSame(['locale' => 'en', 'key' => 'some.existing.key'], $logger->__logs[0]['context']);
        self::assertSame('warning', $logger->__logs[1]['level']);
        self::assertSame('translation: missing {locale} {key}', $logger->__logs[1]['message']);
        self::assertSame(['locale' => 'en', 'key' => 'some.not.existing.key'], $logger->__logs[1]['context']);
    }
}
