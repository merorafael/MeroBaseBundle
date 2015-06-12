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
            $value = preg_replace("[^0-9]", "", $value);
            $value = str_pad($value, 11, "0", STR_PAD_LEFT);
            if (strlen($value) != 11) {
                $this->buildViolation($constraint->message)
                    ->setParameter("{{ value }}", $this->formatValue($value))
                    ->addViolation();
            } else if (
                $value == "00000000000" ||
                $value == "11111111111" ||
                $value == "22222222222" ||
                $value == "33333333333" ||
                $value == "44444444444" ||
                $value == "55555555555" ||
                $value == "66666666666" ||
                $value == "77777777777" ||
                $value == "88888888888" ||
                $value == "99999999999"
            ) {
                $this->buildViolation($constraint->message)
                    ->setParameter("{{ value }}", $this->formatValue($value))
                    ->addViolation();
            } else {
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

}
