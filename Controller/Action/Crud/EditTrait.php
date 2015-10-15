<?php

namespace Mero\Bundle\BaseBundle\Controller\Action\Crud;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

trait EditTrait
{

    /**
     * @return EntityManager Doctrine entity manager
     */
    abstract protected function getDoctrineManager();

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
     */
    abstract protected function getRoute($actionName);

    /**
     * @param string $actionName
     * @param array $actionParams
     * @param bool $error
     */
    abstract protected function getRedirectRoute($actionName, $actionParams, $error);

    /**
     * @param $entity
     *
     * @return Form
     */
    protected function getEditForm($entity)
    {
        $form = $this->createForm($this->createFormType($entity), [
            'action' => $this->generateUrl($this->getRoute($this->getActionName()), [
                'id' => $entity->getId()
            ]),
            'method' => 'PUT'
        ]);
        $form->add('submit', 'submit');
        return $form;
    }

    protected function isEditAuthorized()
    {
        return true;
    }

    public function editAction(Request $request, $id)
    {
        if (!$this->isEditAuthorized()) {
            throw $this->createAccessDeniedException();
        }
        $actionName = $this->getActionName();
        $em = $this->getDoctrineManager();
        $entity = $em->getRepository()->find($id);
        if (!$entity) {
            $this->get("session")
                ->getFlashBag()
                ->add("danger", "mero.base.data_not_found");
            $redirectRoute = $this->getRedirectRoute($actionName, [], true);
            return $this->redirect($this->generateUrl($redirectRoute));
        }
        $form = $this->getEditForm($entity);
        if ($request->isMethod('PUT')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em->persist($entity);
                $em->flush();
                $this->get("session")
                    ->getFlashBag()
                    ->add("success", "mero.base.edit_success");
                $redirectRoute = $this->getRedirectRoute($actionName, [], false);
                return $this->redirect($this->generateUrl($redirectRoute));
            } else {
                $this->get("session")
                    ->getFlashBag()
                    ->add("danger", "mero.base.edit_fail");
                $redirectRoute = $this->getRedirectRoute($actionName, [], true);
                return $this->redirect($this->generateUrl($redirectRoute));
            }
        }
        return $this->render($this->getViewName($actionName), [
            'entity' => $entity,
            'form' => $form->createView()
        ]);
    }

}
