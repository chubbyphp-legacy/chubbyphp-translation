<?php

declare(strict_types=1);

namespace Chubbyphp\Translation;

final class Translator implements TranslatorInterface
{
    /**
     * @var LocaleTranslationProviderInterface[]
     */
    private $localeTranslationProviders = [];

    /**
     * Translator constructor.
     *
     * @param array $localeTranslationProviders
     */
    public function __construct(array $localeTranslationProviders)
    {
        foreach ($localeTranslationProviders as $localeTranslationProvider) {
            $this->addLocaleTranslationProvider($localeTranslationProvider);
        }
    }

    /**
     * @param LocaleTranslationProviderInterface $localeTranslationProvider
     */
    private function addLocaleTranslationProvider(LocaleTranslationProviderInterface $localeTranslationProvider)
    {
        $this->localeTranslationProviders[$localeTranslationProvider->getLocale()] = $localeTranslationProvider;
    }

    /**
     * @param string $locale
     * @param string $key
     * @param array  $args
     *
     * @return string
     */
    public function translate(string $locale, string $key, array $args = []): string
    {
        if (isset($this->localeTranslationProviders[$locale])) {
            return $this->localeTranslationProviders[$locale]->translate($key, $args);
        }

        return $key;
    }

    /**
     * @param string $text
     *
     * @return string
     */
    public function generateKey(string $text): string
    {
        $key = strtolower($text);
        $key = preg_replace('/[^a-zA-Z0-9]/', '', $key);

        return $key;
    }
}
