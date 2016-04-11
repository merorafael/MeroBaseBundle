<?php

namespace Mero\Bundle\BaseBundle\Tests\Twig;

use Mero\Bundle\BaseBundle\Twig\CountryExtension;

class CountryExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CountryExtension
     */
    protected $extension;

    protected function setUp()
    {
        $this->extension = new CountryExtension();
    }

    public static function dataProvider()
    {
        return [
            ['US', 'pt_BR', 'Estados Unidos'],
            ['US', 'en_US', 'United States'],
            ['ES', 'pt_BR', 'Espanha'],
            ['ES', 'en_US', 'Spain'],
            ['PT', 'pt_BR', 'Portugal'],
            ['PT', 'en_US', 'Portugal'],
            ['BR', 'pt_BR', 'Brasil'],
            ['BR', 'en_US', 'Brazil'],
        ];
    }

    public function testEmptyCountryIso()
    {
        $output = $this->extension->getCountryName(null, 'pt_BR');
        $this->assertEmpty($output);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetCountryName($countryIso, $locale, $expected)
    {
        $output = $this->extension->getCountryName($countryIso, $locale);
        $this->assertEquals($expected, $output);
    }
}
