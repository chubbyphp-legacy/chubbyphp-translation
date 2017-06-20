<?php

declare(strict_types=1);

namespace Chubbyphp\Translation;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class Translator implements TranslatorInterface
{
    /**
     * @var LocaleTranslationProviderInterface[]
     */
    private $localeTranslationProviders = [];

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Translator constructor.
     *
     * @param array $localeTranslationProviders
     */
    public function __construct(array $localeTranslationProviders, LoggerInterface $logger = null)
    {
        foreach ($localeTranslationProviders as $localeTranslationProvider) {
            $this->addLocaleTranslationProvider($localeTranslationProvider);
        }
        $this->logger = $logger ?? new NullLogger();
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
            if (null !== $translation = $this->localeTranslationProviders[$locale]->translate($key, $args)) {
                $this->logger->info('translation: translate {locale} {key}', ['locale' => $locale, 'key' => $key]);

                return $translation;
            }

            $this->logger->notice('translation: missing {locale} {key}', ['locale' => $locale, 'key' => $key]);

            return $key;
        }

        $this->logger->warning('translation: missing {locale}', ['locale' => $locale]);

        return $key;
    }
}
