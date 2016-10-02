<?php

namespace Chubbyphp\Translation;

final class TranslationTwigExtension extends \Twig_Extension
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'translation';
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('translate', [$this, 'translate']),
            new \Twig_SimpleFilter('generateKey', [$this, 'generateKey']),
        ];
    }

    /**
     * @param string $key
     * @param string $locale
     * @param array  $args
     *
     * @return string
     */
    public function translate(string $key, string $locale, array $args = []): string
    {
        return $this->translator->translate($locale, $key, $args);
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public function generateKey(string $string): string
    {
        $string = strtolower($string);
        $string = preg_replace('/[^a-zA-Z0-9]/i', '', $string);

        return $string;
    }
}
