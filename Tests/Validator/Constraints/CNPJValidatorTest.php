<?php

namespace Mero\Bundle\BaseBundle\Tests\Validator\Constraints;

use Mero\Bundle\BaseBundle\Validator\Constraints\CNPJ;
use Mero\Bundle\BaseBundle\Validator\Constraints\CNPJValidator;
use Symfony\Component\Validator\Tests\Constraints\AbstractConstraintValidatorTest;
use Symfony\Component\Validator\Validation;

class CNPJValidatorTest extends AbstractConstraintValidatorTest
{
    protected function getApiVersion()
    {
        return Validation::API_VERSION_2_5;
    }

    protected function createValidator()
    {
        return new CNPJValidator();
    }

    public function testNullIsValid()
    {
        $this->validator->validate(null, new CNPJ());
        $this->assertNoViolation();
    }

    public function testEmptyStringIsValid()
    {
        $this->validator->validate('', new CNPJ());
        $this->assertNoViolation();
    }

    public function getInvalidCNPJs()
    {
        return array(
            array('11.111.111/1111-11'),
            array('22.222.222/2222-22'),
            array('66.121.538/0001-00'),
        );
    }

    /**
     * @dataProvider getInvalidCNPJs
     */
    public function testInvalidCNPJs($cnpj)
    {
        $constraint = new CNPJ(array(
            'message' => 'testMessage',
        ));
        $this->validator->validate($cnpj, $constraint);
        $this->buildViolation('testMessage')
            ->setParameter('{{ value }}', '"'.$cnpj.'"')
            ->assertRaised();
    }

    public function getValidCNPJs()
    {
        return array(
            array('06.785.165/0001-00'),
            array('66.121.538/0001-62'),
            array('32.771.783/0001-01'),
        );
    }

    /**
     * @dataProvider getValidCNPJs
     */
    public function testValidCNPJs($cnpj)
    {
        $this->validator->validate($cnpj, new CNPJ());
        $this->assertNoViolation();
    }
}
