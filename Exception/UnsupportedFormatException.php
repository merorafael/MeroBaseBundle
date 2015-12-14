<?php

namespace Mero\Bundle\BaseBundle\Exception;

/**
 * Exception to unsupported format.
 *
 * @author Rafael Mello <merorafael@gmail.com>
 */
class UnsupportedFormatException extends \Exception
{
    public function __toString()
    {
        return __CLASS__.": [{$this->code}]: {$this->message}\n";
    }
}