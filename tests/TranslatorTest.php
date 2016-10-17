<?php

namespace Chubbyphp\Tests\Translation;

use Chubbyphp\Translation\LocaleTranslationProviderInterface;
use Chubbyphp\Translation\Translator;

/**
 * @covers Chubbyphp\Translation\Translator
 */
final class TranslatorTest extends \PHPUnit_Framework_TestCase
{
    use LoggerTestTrait;

    public function testTranslateWithoutArguments()
    {
        $logger = $this->getLogger();

        $translator = new Translator([
            $this->getLocaleTranslationProvider('de', [
                'some.existing.key' => 'erfolgreiche Uebersetzung',
            ]),
            $this->getLocaleTranslationProvider('en', [
                'some.existing.key' => 'successful translation',
            ]),
        ], $logger);

        self::assertSame('erfolgreiche Uebersetzung', $translator->translate('de', 'some.existing.key'));
        self::assertSame('successful translation', $translator->translate('en', 'some.existing.key'));

        self::assertSame('some.not.existing.key', $translator->translate('de', 'some.not.existing.key'));
        self::assertSame('some.not.existing.key', $translator->translate('en', 'some.not.existing.key'));
        self::assertSame('some.not.existing.key', $translator->translate('fr', 'some.not.existing.key'));

        self::assertCount(1, $logger->__logs);
        self::assertSame('notice', $logger->__logs[0]['level']);
        self::assertSame('translation: missing {locale}', $logger->__logs[0]['message']);
        self::assertSame(['locale' => 'fr'], $logger->__logs[0]['context']);
    }

    public function testTranslateWithArguments()
    {
        $logger = $this->getLogger();

        $translator = new Translator([
            $this->getLocaleTranslationProvider('de', [
                'some.existing.key' => '%d erfolgreiche Uebersetzungen',
            ]),
            $this->getLocaleTranslationProvider('en', [
                'some.existing.key' => '%d successful translations',
            ]),
        ], $logger);

        self::assertSame('5 erfolgreiche Uebersetzungen', $translator->translate('de', 'some.existing.key', [5]));
        self::assertSame('5 successful translations', $translator->translate('en', 'some.existing.key', [5]));

        self::assertSame('some.not.existing.key', $translator->translate('de', 'some.not.existing.key', [5]));
        self::assertSame('some.not.existing.key', $translator->translate('en', 'some.not.existing.key', [5]));
        self::assertSame('some.not.existing.key', $translator->translate('fr', 'some.not.existing.key', [5]));

        self::assertCount(1, $logger->__logs);
        self::assertSame('notice', $logger->__logs[0]['level']);
        self::assertSame('translation: missing {locale}', $logger->__logs[0]['message']);
        self::assertSame(['locale' => 'fr'], $logger->__logs[0]['context']);
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
