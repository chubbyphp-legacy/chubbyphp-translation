<?php

namespace Chubbyphp\Translation;

interface TranslatorInterface
{
    /**
     * @param string $locale
     * @param string $key
     * @param array  $args
     *
     * @return string
     */
    public function translate(string $locale, string $key, array $args = []): string;

    /**
     * @param string $text
     *
     * @return string
     */
    public function generateKey(string $text): string;
}
