<?php
namespace Mero\Bundle\BaseBundle\Controller;

use Mero\Bundle\BaseBundle\Exception\InvalidEntityException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @package Mero\Bundle\BaseBundle\Controller
 */
abstract class StdController extends Controller
{

    /**
     * Retorna nome do método de action.
     *
     * @param string $method_constant Constante __METHOD__
     *
     * @return string
     */
    protected function getActionName($method_constant)
    {
        $action = preg_split("/[\:]+/", $method_constant);
        return $action[1];
    }

    /**
     * Return current bundle name.
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
     * Retorna exceção de InvalidEntityException.
     *
     * Exemplo de uso:
     *
     *     throw $this->createInvalidEntityException('Invalid entity');
     *
     * @param string $message  Mensagem de exceção
     * @param \Exception|null $previous Exceção anterior
     *
     * @return InvalidEntityException
     */
    protected function createInvalidEntityException($message = 'Entity is not object', \Exception $previous = null)
    {
        return new InvalidEntityException($message, $previous);
    }

}
