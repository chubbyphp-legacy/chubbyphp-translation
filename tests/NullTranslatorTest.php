<?php

namespace Chubbyphp\Tests\Translation;

use Chubbyphp\Translation\NullTranslator;

/**
 * @covers \Chubbyphp\Translation\NullTranslator
 */
final class NullTranslatorTest extends \PHPUnit_Framework_TestCase
{
    public function testTranslateWithoutArguments()
    {
        $translator = new NullTranslator();

        self::assertSame('some.not.existing.key', $translator->translate('de', 'some.not.existing.key'));
        self::assertSame('some.not.existing.key', $translator->translate('en', 'some.not.existing.key'));
        self::assertSame('some.not.existing.key', $translator->translate('fr', 'some.not.existing.key'));
    }
}
