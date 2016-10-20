<?php

declare(strict_types=1);

namespace Chubbyphp\Translation;

interface LocaleTranslationProviderInterface
{
    /**
     * @return string
     */
    public function getLocale(): string;

    /**
     * @param string $key
     * @param array  $args
     *
     * @return string|null
     */
    public function translate(string $key, array $args);
}
