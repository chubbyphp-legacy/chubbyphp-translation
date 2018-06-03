<?php

declare(strict_types=1);

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
     * @param array|string[] $translations
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
     * @return string|null
     */
    public function translate(string $key, array $arguments = [])
    {
        if (!isset($this->translations[$key])) {
            return null;
        }

        if (!$this->hasNamedArguments($arguments)) {
            return $this->translateWithoutNamedArguments($key, $arguments);
        }

        return $this->translateWithNamedArguments($key, $arguments);
    }

    /**
     * @param array $arguments
     *
     * @return bool
     */
    private function hasNamedArguments(array $arguments): bool
    {
        foreach (array_keys($arguments) as $name) {
            if (!is_numeric($name)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $key
     * @param array  $arguments
     *
     * @return string
     */
    private function translateWithoutNamedArguments(string $key, array $arguments = []): string
    {
        return sprintf($this->translations[$key], ...$arguments);
    }

    /**
     * @param string $key
     * @param array  $arguments
     *
     * @return string
     */
    private function translateWithNamedArguments(string $key, array $arguments = []): string
    {
        $translation = $this->translations[$key];
        foreach ($arguments as $name => $value) {
            $translation = str_replace('{{'.$name.'}}', $value, $translation);
        }

        return $translation;
    }
}
