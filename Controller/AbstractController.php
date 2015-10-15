<?php

namespace Mero\Bundle\BaseBundle\Controller;

use Mero\Bundle\BaseBundle\Exception\InvalidEntityException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AbstractController extends Controller
{

    /**
     * Gets the current request object.
     *
     * @return Request
     */
    protected function getCurrentRequest()
    {
        return $this->container->get('request_stack')->getCurrentRequest();
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
        $matches = [];
        $className = str_replace('\Bundle\\', '', get_class($this));
        preg_match('/(.*)\\\Controller/', $className, $matches);
        return count($matches) != 0
            ? $matches[1]
            : null;
    }

    /**
     * Gets the action name.
     *
     * @return string Action name
     */
    protected function getActionName()
    {
        $request = $this->getCurrentRequest();
        $action = explode('::', $request->attributes->get('_controller'));
        return $action[1];
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
