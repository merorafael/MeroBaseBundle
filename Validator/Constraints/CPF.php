<?php
namespace Mero\BaseBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 *
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 *
 * @author Rafael Mello <merorafael@gmail.com>
 *
 * @api
 */
class CPF extends Constraint
{

    public $message = "O CPF informado não é válido.";

}
