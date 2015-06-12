<?php
namespace Mero\BaseBundle\Exception;

/**
 * Exception to invalid entity(not object).
 *
 * @package Mero\BaseBundle\Exception
 * @author Rafael Mello <merorafael@gmail.com>
 */
class InvalidEntityException extends \Exception
{

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

}
