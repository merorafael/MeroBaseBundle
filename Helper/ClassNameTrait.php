<?php

namespace Mero\Bundle\BaseBundle\Helper;

/**
 * @author Rafael Mello <merorafael@gmail.com>
 */
trait ClassNameTrait
{
    /**
     * Return the class name.
     *
     * @return string Class name
     *
     * @deprecated Not use this method if you're using PHP 5.5 or above. As of PHP 5.5,
     * there is native constant named "::class".
     */
    public static function className()
    {
        return get_called_class();
    }
}
