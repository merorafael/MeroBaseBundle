<?php
namespace Mero\Bundle\BaseBundle\Controller;

use Mero\Bundle\BaseBundle\Exception\InvalidEntityException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @package Mero\Bundle\BaseBundle\Controller
 * @author Rafael Mello <merorafael@gmail.com>
 * @Copyright Copyright (c) 2014~2015 - Rafael Mello
 * @license https://github.com/merorafael/MeroBaseBundle/blob/master/LICENSE MIT license
 */
abstract class StdController extends Controller
{

    /**
     * Gets the route name.
     *
     * @param Request $request Action request injection
     *
     * @return string Route name
     */
    protected function getRouteName(Request $request)
    {
        return $request->attributes->get('_route');
    }

    /**
     * Gets the action name.
     *
     * @param Request $request Action request injection
     *
     * @return string Action name
     */
    protected function getActionName(Request $request)
    {
        $action = explode('::', $request->attributes->get('_controller'));
        return $action[1];
    }

    /**
     * Gets the bundle name.
     *
     * <b>Attention! This method returns the bundle name as the conventions of framewok.</b>
     * Example: "Mero/Bundle/BaseBundle" or "Mero/BaseBundle" returns "MeroBaseBundle".
     *
     * @return string
     */
    protected function getBundleName()
    {
        $current_class = explode("\\", get_class($this));
        if (in_array("Bundle", $current_class, true)) {
            unset($current_class[array_search("Bundle", $current_class, true)]);
        }
        $bundle_name = "";
        foreach ($current_class as &$directory) {
            $bundle_name .= $directory;
            if (strpos($directory, "Bundle")) {
                break;
            }
        }
        return $bundle_name;
    }

    /**
     * Returns a InvalidEntityException.
     *
     * This method returns an invalid entity exception. Usage exemple:
     *
     *     throw $this->createInvalidEntityException('Invalid entity');
     *
     * @param string $message A message
     * @param \Exception|null $previous The previous exception
     *
     * @return InvalidEntityException
     */
    protected function createInvalidEntityException($message = 'Entity is not object', \Exception $previous = null)
    {
        return new InvalidEntityException($message, $previous);
    }

}
