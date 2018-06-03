# chubbyphp-translation

[![Build Status](https://api.travis-ci.org/chubbyphp/chubbyphp-translation.png?branch=master)](https://travis-ci.org/chubbyphp/chubbyphp-translation)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/chubbyphp/chubbyphp-translation/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/chubbyphp/chubbyphp-translation/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/chubbyphp/chubbyphp-translation/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/chubbyphp/chubbyphp-translation/?branch=master)
[![Total Downloads](https://poser.pugx.org/chubbyphp/chubbyphp-translation/downloads.png)](https://packagist.org/packages/chubbyphp/chubbyphp-translation)
[![Monthly Downloads](https://poser.pugx.org/chubbyphp/chubbyphp-translation/d/monthly)](https://packagist.org/packages/chubbyphp/chubbyphp-translation)
[![Latest Stable Version](https://poser.pugx.org/chubbyphp/chubbyphp-translation/v/stable.png)](https://packagist.org/packages/chubbyphp/chubbyphp-translation)
[![Latest Unstable Version](https://poser.pugx.org/chubbyphp/chubbyphp-translation/v/unstable)](https://packagist.org/packages/chubbyphp/chubbyphp-translation)

## Description

A simple translation solution.

## Requirements

 * php: ~7.0

## Suggest

 * pimple/pimple: ~3.0
 * twig/twig: ^1.25.0

## Installation

Through [Composer](http://getcomposer.org) as [chubbyphp/chubbyphp-translation][1].

```sh
composer require chubbyphp/chubbyphp-translation "~1.1"
```

## Usage

### Translator

```php
<?php

use Chubbyphp\Translation\LocaleTranslationProvider;
use Chubbyphp\Translation\Translator;

$translator = new Translator([
    new LocaleTranslationProvider('de', [
        'some.existing.key' => 'erfolgreiche Uebersetzung',
        'another.existing.key' => '%d erfolgreiche Uebersetzungen',
        'yetanother.existing.key' => '{{key}} erfolgreiche Uebersetzungen'
    ]),
    new LocaleTranslationProvider('en', [
        'some.existing.key' => 'successful translation',
        'another.existing.key' => '%d successful translations'
        'yetanother.existing.key' => '{{key}} successful translations'
    ])
]);

echo $translator->translate('de', 'some.existing.key'); // erfolgreiche Uebersetzung
echo $translator->translate('en', 'some.existing.key'); // successful translation
echo $translator->translate('fr', 'some.existing.key'); // some.existing.key

echo $translator->translate('de', 'another.existing.key', [5]); // 5 erfolgreiche Uebersetzungen
echo $translator->translate('en', 'another.existing.key', [5]); // 5 successful translations
echo $translator->translate('fr', 'another.existing.key', [5]); // some.existing.key

echo $translator->translate('de', 'yetanother.existing.key', ['key' => 5]); // 5 erfolgreiche Uebersetzungen
echo $translator->translate('en', 'yetanother.existing.key', ['key' => 5]); // 5 successful translations
echo $translator->translate('fr', 'yetanother.existing.key', ['key' => 5]); // some.existing.key
```

### TranslationProvider (Pimple)

```php
<?php

use Chubbyphp\Translation\Translator;
use Chubbyphp\Translation\TranslationProvider;
use Pimple\Container;

$container->register(new TranslationProvider);

$container->extend('translator.providers', function (array $providers) use ($container) {
    $providers[] = new LocaleTranslationProvider('de', [
        'some.existing.key' => 'erfolgreiche Uebersetzung',
        'another.existing.key' => '%d erfolgreiche Uebersetzungen'
    ]);
    $providers[] = new LocaleTranslationProvider('en', [
        'some.existing.key' => 'successful translation',
        'another.existing.key' => '%d successful translations'
    ]);

    return $providers;
});

/** @var Translation $translator */
$translator = $container['translator'];
```

### TranslationTwigExtension

```php
<?php

use Chubbyphp\Translation\LocaleTranslationProvider;
use Chubbyphp\Translation\TranslationTwigExtension;
use Chubbyphp\Translation\Translator;

$twig->addExtension(new TranslationTwigExtension(new Translator([])));
```

```twig
{{ 'some.existing.key'|translate('de') }}
{{ 'another.existing.key'|translate('de', [5]) }}
{{ 'yetanother.existing.key'|translate('de', ['key' => 5]) }}
```

[1]: https://packagist.org/packages/chubbyphp/chubbyphp-translation

## Copyright

Dominik Zogg 2016
