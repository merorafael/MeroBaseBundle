<?php

namespace Mero\Bundle\BaseBundle\Tests\Twig;

use Mero\Bundle\BaseBundle\Twig\LanguageExtension;

class LanguageExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LanguageExtension
     */
    protected $extension;

    protected function setUp()
    {
        $this->extension = new LanguageExtension();
    }

    public static function dataProvider()
    {
        return [
            ['en', 'pt_BR', 'inglês'],
            ['en', 'en_US', 'English'],
            ['en_US', 'pt_BR', 'inglês'],
            ['en_US', 'en_US', 'English'],
            ['es', 'pt_BR', 'espanhol'],
            ['es', 'en_US', 'Spanish'],
            ['es_ES', 'pt_BR', 'espanhol'],
            ['es_ES', 'en_US', 'Spanish'],
        ];
    }

    public function testEmptyLanguageIso()
    {
        $output = $this->extension->getLanguageName(null, 'pt_BR');
        $this->assertEmpty($output);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetLanguageName($languageIso, $locale, $expected)
    {
        $output = $this->extension->getLanguageName($languageIso, $locale);
        $this->assertEquals($expected, $output);
    }
}
