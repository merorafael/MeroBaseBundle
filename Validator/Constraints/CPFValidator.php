<?php
namespace Mero\BaseBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @author Rafael Mello <merorafael@gmail.com>
 *
 * @api
 */
class CPFValidator extends ConstraintValidator
{

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof CPF) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . "\CPF");
        }
        if (!empty($value)) {
            $value = preg_replace("/[^0-9]/", "", $value);
            if (strlen($value) != 11) {
                $this->buildViolation($constraint->message)
                    ->setParameter("{{ value }}", $this->formatValue($value))
                    ->addViolation();
            }
            for ($t = 9; $t < 11; $t++) {
                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $value{$c} * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($value{$c} != $d) {
                    $this->buildViolation($constraint->message)
                        ->setParameter("{{ value }}", $this->formatValue($value))
                        ->addViolation();
                }
            }
        }
    }

}
