<?php

namespace Mero\Bundle\BaseBundle\Tests\Helper;

use Mero\Bundle\BaseBundle\Helper\ClassNameTrait;

class ClassNameTraitTest extends \PHPUnit_Framework_TestCase
{
    use ClassNameTrait;

    public function testClassName()
    {
        $className = self::className();
        $this->assertEquals(
            'Mero\Bundle\BaseBundle\Tests\Helper\ClassNameTraitTest',
            $className
        );
    }
}
