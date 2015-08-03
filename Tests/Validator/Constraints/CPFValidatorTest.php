<?php
namespace Mero\Bundle\BaseBundle\Tests\Validator\Constraints;

use Mero\Bundle\BaseBundle\Validator\Constraints\CPF;
use Mero\Bundle\BaseBundle\Validator\Constraints\CPFValidator;
use Symfony\Component\Validator\Tests\Constraints\AbstractConstraintValidatorTest;
use Symfony\Component\Validator\Validation;

/**
 * @package Mero\Bundle\BaseBundle\Tests\Validator\Constraints
 */
class CPFValidatorTest extends AbstractConstraintValidatorTest
{

    protected function getApiVersion()
    {
        return Validation::API_VERSION_2_5;
    }

    protected function createValidator()
    {
        return new CPFValidator();
    }

    public function testNullIsValid()
    {
        $this->validator->validate(null, new CPF());
        $this->assertNoViolation();
    }

    public function testEmptyStringIsValid()
    {
        $this->validator->validate('', new CPF());
        $this->assertNoViolation();
    }


    public function getInvalidCPFs()
    {
        return array(
            array('111.111.111-11'),
            array('222.222.222-22'),
            array('398.682.528-23')
        );
    }

    /**
     * @dataProvider getInvalidCPFs
     */
    public function testInvalidCPFs($cpf)
    {
        $constraint = new CPF(array(
            "message" => "testMessage"
        ));
        $this->validator->validate($cpf, $constraint);
        $this->buildViolation('testMessage')
            ->setParameter('{{ value }}', '"'.$cpf.'"')
            ->assertRaised();
    }

    public function getValidCPFs()
    {
        return array(
            array('398.682.528-22'),
            array('534.005.933-20'),
            array('235.515.623-93')
        );
    }

    /**
     * @dataProvider getValidCPFs
     */
    public function testValidCPFs($cpf)
    {
        $this->validator->validate($cpf, new CPF());
        $this->assertNoViolation();
    }

}
