<?php

namespace Mero\Bundle\BaseBundle\Controller\Action\Crud;

use Mero\Bundle\BaseBundle\Entity\AbstractEntity;
use Symfony\Component\HttpFoundation\Request;

trait CreateTrait
{
    /**
     * @return EntityManager Doctrine entity manager
     */
    abstract protected function getDoctrineManager();

    abstract protected function createNewEntity();

    /**
     * @param string $actionName
     */
    abstract protected function getRoute($actionName);

    /**
     * @return AbstractType
     */
    abstract protected function createFormType($entity);

    /**
     * @param string $actionName
     *
     * @return string
     */
    abstract protected function getViewName($actionName);

    /**
     * @param string $actionName
     * @param array  $actionParams
     * @param bool   $error
     */
    abstract protected function getRedirectRoute($actionName, $actionParams, $error);

    protected function isCreateAuthorized()
    {
        return true;
    }

    /**
     * @param AbstractEntity $entity
     *
     * @return Form
     */
    protected function getCreateForm(AbstractEntity $entity)
    {
        $form = $this->createForm($this->createFormType($entity), [
            'action' => $this->generateUrl($this->getRoute($this->getActionName())),
            'method' => 'POST',
        ]);
        $form->add('submit', 'submit');

        return $form;
    }

    public function createAction(Request $request)
    {
        if (!$this->isCreateAuthorized()) {
            throw $this->createAccessDeniedException();
        }
        $actionName = $this->getActionName();
        $newEntity = $this->createNewEntity();
        $form = $this->getCreateForm($newEntity);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrineManager();
                $em->persist($newEntity);
                $em->flush();
                $this->get('session')
                    ->getFlashBag()
                    ->add('success', 'mero.base.create_success');
                $redirectRoute = $this->getRedirectRoute($actionName, [], false);

                return $this->redirect($this->generateUrl($redirectRoute));
            } else {
                $this->get('session')
                    ->getFlashBag()
                    ->add('danger', 'mero.base.create_fail');
                $redirectRoute = $this->getRedirectRoute($actionName, [], true);

                return $this->redirect($this->generateUrl($redirectRoute));
            }
        }

        return $this->render($this->getViewName($actionName), [
            'entity' => $newEntity,
            'form' => $form->createView(),
        ]);
    }
}
