<?php
namespace Mero\Bundle\BaseBundle\Twig;

/**
 * Country filter for Twig.
 *
 * @package Mero\Bundle\BaseBundle\Twig
 * @author Rafael Mello <merorafael@gmail.com>
 * @license https://github.com/merorafael/MeroBaseBundle/blob/master/LICENSE MIT license
 */
class CountryExtension extends \Twig_Extension
{

    public function __construct()
    {
        if (!class_exists("\Locale")) {
            throw new \RuntimeException('The country extension is needed to use intl-based filters.');
        }
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter("country", array($this, "getCountryName")),
        );
    }

    /**
     * Return the country name using the Locale class.
     *
     * @param string $iso_code Country ISO 3166-1 alpha 2 code
     * @param null|string $locale Locale code
     *
     * @return null|string Country name
     */
    public function getCountryName($iso_code, $locale = null)
    {
        if ($iso_code === null) {
            return null;
        }
        return $locale === null ? \Locale::getDisplayRegion("unq_".$iso_code) : \Locale::getDisplayRegion("unq_".$iso_code, $locale);
    }

    public function getName()
    {
        return "country_extension";
    }

}
