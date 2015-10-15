<?php

namespace Mero\Bundle\BaseBundle\Controller\Action\Crud;

trait DeleteTrait
{
    /**
     * @return EntityManager Doctrine entity manager
     */
    abstract protected function getDoctrineManager();

    abstract protected function getEntityName();

    /**
     * @param string $actionName
     * @param array  $actionParams
     * @param bool   $error
     *
     * @return string
     */
    abstract protected function getRedirectRoute($actionName, $actionParams, $error);

    protected function isDeleteAuthorized()
    {
        return true;
    }

    public function deleteAction($id)
    {
        if (!$this->isDeleteAuthorized()) {
            throw $this->createAccessDeniedException();
        }
        $em = $this->getDoctrineManager();
        $entity = $em->getRepository($this->getEntityName())->find($id);
        if (!$entity) {
            $this->get('session')
                ->getFlashBag()
                ->add('danger', 'mero.base.data_not_found');
            $redirect_route = $this->getRedirectRoute($this->getActionName(), [$id], true);
            if ($redirect_route !== null) {
                return $this->redirect($this->generateUrl($redirect_route));
            }
        } else {
            $em->remove($entity);
            $em->flush();
            $this->get('session')
                ->getFlashBag()
                ->add('success', 'mero.base.delete_success');
        }

        return $this->redirect($this->generateUrl($this->getRedirectRoute($this->getActionName(), [$id], false)));
    }
}
