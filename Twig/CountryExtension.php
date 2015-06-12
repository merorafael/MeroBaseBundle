<?php
namespace Mero\BaseBundle\Twig;

/**
 * Country filter
 *
 * @package Mero\BaseBundle\Twig
 * @author Rafael Mello <merorafael@gmail.com>
 * @license https://github.com/merorafael/MeroBaseBundle/blob/master/LICENSE MIT license
 */
class CountryExtension extends \Twig_Extension
{

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter("country", array($this, "getCountryName")),
        );
    }

    public function getCountryName($iso_code, $locale = null)
    {
        return (($locale === null) || ($iso_code === null)) ? null :
            \Locale::getDisplayRegion("unq_".$iso_code, $locale);
    }

    public function getName()
    {
        return "country_extension";
    }

}
