<?php

namespace Mero\Bundle\BaseBundle\Twig;

use Symfony\Component\Intl\Intl;

/**
 * Language filter for Twig.
 *
 * @author Rafael Mello <merorafael@gmail.com>
 * @license https://github.com/merorafael/MeroBaseBundle/blob/master/LICENSE MIT license
 */
class LanguageExtension extends \Twig_Extension
{
    public function __construct()
    {
        if (!class_exists('\Locale')) {
            throw new \RuntimeException('The country extension is needed to use intl-based filters.');
        }
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('language', [
                $this,
                'getLanguageName',
            ]),
        );
    }

    /**
     * Return the country name using the Locale class.
     *
     * @param string      $isoCode Country ISO 3166-1 alpha 2 code
     * @param null|string $locale  Locale code
     *
     * @return null|string Country name
     */
    public function getLanguageName($isoCode, $locale = null)
    {
        if ($isoCode === null) {
            return;
        }
        if ($locale) {
            \Locale::setDefault($locale);
        }

        return \Locale::getDisplayLanguage($isoCode, $locale);
    }

    public function getName()
    {
        return 'language_extension';
    }
}
