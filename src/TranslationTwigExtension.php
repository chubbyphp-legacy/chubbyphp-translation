<?php

declare(strict_types=1);

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
}
