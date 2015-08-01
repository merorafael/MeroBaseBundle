<?php
namespace Mero\Bundle\BaseBundle\Validator\Constraints;

use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @package Mero\Bundle\BaseBundle\Validator\Constraints
 * @author Rafael Mello <merorafael@gmail.com>
 * @api
 */
class BrazilianCNPJValidator extends ConstraintValidator
{

    /**
     * @inheritDoc
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof BrazilianCNPJ)
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . "\BrazilianCNPJ");
        if (!empty($value)) {
            $value = preg_replace("/[^0-9]/", "", $value);
            if (strlen($value) != 14)
                $this->buildViolation($constraint->message)
                    ->setParameter("{{ value }}", $this->formatValue($value))
                    ->addViolation();

        }
    }

}
