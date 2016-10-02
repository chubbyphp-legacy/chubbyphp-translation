<?php

namespace Chubbyphp\Translation;

final class LocaleTranslationProvider implements LocaleTranslationProviderInterface
{
    /**
     * @var string
     */
    private $locale;

    /**
     * @var string[]|array
     */
    private $translations;

    /**
     * @param array|\string[] $translations
     */
    public function __construct(string $locale, array $translations)
    {
        $this->locale = $locale;
        $this->translations = $translations;
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * @param string $key
     * @param array  $arguments
     *
     * @return string
     */
    public function translate(string $key, array $arguments = []): string
    {
        if (isset($this->translations[$key])) {
            return sprintf($this->translations[$key], ...$arguments);
        }

        return $key;
    }
}
