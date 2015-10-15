<?php

namespace Mero\Bundle\BaseBundle\Controller;

use Mero\Bundle\BaseBundle\Controller\Action\Crud;
use Symfony\Component\Form\AbstractType;

abstract class AbstractCrudController extends AbstractController
{

    /**
     * @return mixed
     */
    abstract protected function createNewEntity();

    /**
     * @param string $actionName
     *
     * @return string
     */
    abstract protected function getRoute($actionName);

    /**
     * @return AbstractType
     */
    abstract protected function createFormType($entity);

    /**
     * Gets view name.
     *
     * @param string $actionName
     *
     * @return string
     */
    abstract protected function getViewName($actionName);

    /**
     * @param string $actionName
     * @param array $actionParams
     * @param bool $error
     *
     * @return string
     */
    abstract protected function getRedirectRoute($actionName, $actionParams, $error);

    protected function getDoctrineManager()
    {
        return $this->getDoctrine()->getManager();
    }

    final protected function getEntityName()
    {
        $entity = $this->createNewEntity();
        if (!is_object($entity)) {
            throw $this->createInvalidEntityException();
        }
        return get_class($entity);
    }

    final protected function getEntityNamespace()
    {
        $entity_namespace = explode("\\", $this->getEntityName());
        array_pop($entity_namespace);
        return "\\".implode("\\", $entity_namespace);
    }

    final protected function getEntityClassname()
    {
        $entity = explode("\\", $this->getEntityName());
        return end($entity);
    }

    use Crud\IndexTrait;

    use Crud\CreateTrait;

    use Crud\EditTrait;

    use Crud\DeleteTrait;
}
