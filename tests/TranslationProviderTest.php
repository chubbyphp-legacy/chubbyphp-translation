<?php

namespace Chubbyphp\Tests\Translation;

use Chubbyphp\Translation\TranslationProvider;
use Chubbyphp\Translation\Translator;
use PHPUnit\Framework\TestCase;
use Pimple\Container;

/**
 * @covers \Chubbyphp\Translation\TranslationProvider
 */
final class TranslationProviderTest extends TestCase
{
    public function testRegister()
    {
        $container = new Container();
        $container->register(new TranslationProvider());

        self::assertTrue(isset($container['translator.providers']));
        self::assertTrue(isset($container['translator']));

        self::assertSame([], $container['translator.providers']);
        self::assertInstanceOf(Translator::class, $container['translator']);
    }
}
