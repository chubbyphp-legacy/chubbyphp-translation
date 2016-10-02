<?php

namespace Chubbyphp\Tests\Translation;

use Chubbyphp\Translation\LocaleTranslationProviderInterface;
use Chubbyphp\Translation\Translator;

/**
 * @covers Chubbyphp\Translation\Translator
 */
final class TranslatorTest extends \PHPUnit_Framework_TestCase
{
    public function testTranslateWithoutArguments()
    {
        $translator = new Translator([
            $this->getLocaleTranslationProvider('de', [
                'some.existing.key' => 'erfolgreiche Uebersetzung',
            ]),
            $this->getLocaleTranslationProvider('en', [
                'some.existing.key' => 'successful translation',
            ]),
        ]);

        self::assertSame('erfolgreiche Uebersetzung', $translator->translate('de', 'some.existing.key'));
        self::assertSame('successful translation', $translator->translate('en', 'some.existing.key'));

        self::assertSame('some.not.existing.key', $translator->translate('de', 'some.not.existing.key'));
        self::assertSame('some.not.existing.key', $translator->translate('en', 'some.not.existing.key'));
        self::assertSame('some.not.existing.key', $translator->translate('fr', 'some.not.existing.key'));
    }

    public function testTranslateWithArguments()
    {
        $translator = new Translator([
            $this->getLocaleTranslationProvider('de', [
                'some.existing.key' => '%d erfolgreiche Uebersetzungen',
            ]),
            $this->getLocaleTranslationProvider('en', [
                'some.existing.key' => '%d successful translations',
            ]),
        ]);

        self::assertSame('5 erfolgreiche Uebersetzungen', $translator->translate('de', 'some.existing.key', [5]));
        self::assertSame('5 successful translations', $translator->translate('en', 'some.existing.key', [5]));

        self::assertSame('some.not.existing.key', $translator->translate('de', 'some.not.existing.key', [5]));
        self::assertSame('some.not.existing.key', $translator->translate('en', 'some.not.existing.key', [5]));
        self::assertSame('some.not.existing.key', $translator->translate('fr', 'some.not.existing.key', [5]));
    }

    public function testGenerateKey()
    {
        $translator = new Translator([]);

        self::assertSame(
            'thisisarandomtextthatneedsatleast1translationkey',
            $translator->generateKey('This is a random text that needs at least 1 translation key')
        );
    }

    /**
     * @param string $locale
     * @param array  $translations
     *
     * @return LocaleTranslationProviderInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getLocaleTranslationProvider(string $locale, array $translations)
    {
        $provider = $this
            ->getMockBuilder(LocaleTranslationProviderInterface::class)
            ->setMethods(['getLocale', 'translate'])
            ->getMockForAbstractClass()
        ;

        $provider->expects(self::any())->method('getLocale')->willReturn($locale);

        $provider
            ->expects(self::any())
            ->method('translate')
            ->willReturnCallback(
                function (string $key, array $arguments) use ($translations) {
                    if (isset($translations[$key])) {
                        return sprintf($translations[$key], ...$arguments);
                    }

                    return $key;
                }
            )
        ;

        return $provider;
    }
}
