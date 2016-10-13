<?php

declare(strict_types=1);

namespace Chubbyphp\Translation;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

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
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param array|\string[] $translations
     */
    public function __construct(string $locale, array $translations, LoggerInterface $logger = null)
    {
        $this->locale = $locale;
        $this->translations = $translations;
        $this->logger = $logger ?? new NullLogger();
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
            $this->logger->info('translation: translate {locale} {key}', ['locale' => $this->locale, 'key' => $key]);

            return sprintf($this->translations[$key], ...$arguments);
        }

        $this->logger->warning('translation: missing {locale} {key}', ['locale' => $this->locale, 'key' => $key]);

        return $key;
    }
}
