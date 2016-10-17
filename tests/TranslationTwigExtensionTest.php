<?php

namespace Chubbyphp\Tests\Translation;

use Chubbyphp\Translation\TranslationTwigExtension;
use Chubbyphp\Translation\TranslatorInterface;

/**
 * @covers Chubbyphp\Translation\TranslationTwigExtension
 */
final class TranslationTwigExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testGetName()
    {
        $extension = new TranslationTwigExtension($this->getTranslator([]));

        self::assertSame('translation', $extension->getName());
    }

    public function testGetFilters()
    {
        $extension = new TranslationTwigExtension($this->getTranslator([]));

        $filters = $extension->getFilters();

        self::assertCount(1, $filters);

        /** @var \Twig_SimpleFilter $translateFilter */
        $translateFilter = $filters[0];

        self::assertInstanceOf(\Twig_SimpleFilter::class, $translateFilter);
        self::assertSame('translate', $translateFilter->getName());
        self::assertSame([$extension, 'translate'], $translateFilter->getCallable());
    }

    public function testTranslateWithoutArguments()
    {
        $extension = new TranslationTwigExtension(
            $this->getTranslator([
                'de' => ['some.existing.key' => 'erfolgreiche Uebersetzung'],
                'en' => ['some.existing.key' => 'successful translation'],
            ])
        );

        self::assertSame('erfolgreiche Uebersetzung', $extension->translate('some.existing.key', 'de'));
        self::assertSame('successful translation', $extension->translate('some.existing.key', 'en'));
        self::assertSame('some.existing.key', $extension->translate('some.existing.key', 'fr'));
    }

    public function testTranslateWithArguments()
    {
        $extension = new TranslationTwigExtension(
            $this->getTranslator([
                'de' => ['some.existing.key' => '%d erfolgreiche Uebersetzungen'],
                'en' => ['some.existing.key' => '%d successful translations'],
            ])
        );

        self::assertSame('5 erfolgreiche Uebersetzungen', $extension->translate('some.existing.key', 'de', [5]));
        self::assertSame('5 successful translations', $extension->translate('some.existing.key', 'en', [5]));
        self::assertSame('some.existing.key', $extension->translate('some.existing.key', 'fr', [5]));
    }

    /**
     * @param array $translations
     *
     * @return TranslatorInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getTranslator(array $translations)
    {
        $provider = $this
            ->getMockBuilder(TranslatorInterface::class)
            ->setMethods(['translate'])
            ->getMockForAbstractClass()
        ;

        $provider
            ->expects(self::any())
            ->method('translate')
            ->willReturnCallback(
                function (string $locale, string $key, array $arguments) use ($translations) {
                    if (isset($translations[$locale][$key])) {
                        return sprintf($translations[$locale][$key], ...$arguments);
                    }

                    return $key;
                }
            )
        ;

        return $provider;
    }
}
