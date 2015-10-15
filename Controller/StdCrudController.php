<?php

namespace Mero\Bundle\BaseBundle\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Mero\Bundle\BaseBundle\Exception\InvalidEntityException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Rafael Mello <merorafael@gmail.com>
 * @Copyright Copyright (c) 2014~2015 - Rafael Mello
 *
 * @license https://github.com/merorafael/MeroBaseBundle/blob/master/LICENSE MIT license
 */
abstract class StdCrudController extends StdController
{
    /**
     * Gets a named object manager.
     *
     * @param string $name The object manager name (null for the default one)
     *
     * @return EntityManager
     */
    protected function getDoctrineManager($name = null)
    {
        return $this->getDoctrine()->getManager($name);
    }

    /**
     * Retorna campo usado por padrão para ordenação dos dados na indexAction.
     *
     * @return string
     */
    abstract protected function defaultSort();

    /**
     * Verificador de CRUD na indexAction.
     *
     * @return bool
     */
    protected function isIndexCrud()
    {
        return $this->getParameter('mero_base.index_crud');
    }

    /**
     * Verificador de conteúdo paginado no indexAction.
     *
     * @return bool
     */
    protected function isDataPagination()
    {
        return true;
    }

    /**
     * Verifica se usuário possui autorização para acessar indexAction.
     *
     * @return bool
     */
    protected function isIndexActionAuthorized()
    {
        return true;
    }

    /**
     * Verifica se usuário possui autorização para acessar detailsAction.
     *
     * @return bool
     */
    protected function isDetailsActionAuthorized()
    {
        return true;
    }

    /**
     * Verifica se usuário possui autorização para acessar addAction.
     *
     * @return bool
     */
    protected function isAddActionAuthorized()
    {
        return true;
    }

    /**
     * Verifica se usuário possui autorização para acessar editAction.
     *
     * @return bool
     */
    protected function isEditActionAuthorized()
    {
        return true;
    }

    /**
     * Verifica se usuário possui autorização para acessar removeAction.
     *
     * @return bool
     */
    protected function isRemoveActionAuthorized()
    {
        return true;
    }

    /**
     * Retorna rota de direcionamento pós-processamento.
     *
     * @param string $origin_action Página solicitante(indexAction, addAction, editAction ou removeAction)
     * @param bool   $fail          Identificador de falha ocorrida durante processamento
     *
     * @return null|string
     */
    abstract protected function getRedirectRoute($origin_action, $fail = false);

    /**
     * Retorna formulário de filtro, caso exista.
     *
     * @return null|Form
     */
    protected function getFilterForm()
    {
        return;
    }

    /**
     * Retorna entidade nova instanciada como objeto.
     *
     * @return object
     */
    abstract protected function getNewEntityObject();

    /**
     * Retorna objeto de formulário.
     *
     * @return AbstractType
     */
    abstract protected function getFormType();

    /**
     * Retorna nome da entidade incluindo namespace. Ex: Mero\Bundle\BaseBundle\Entity\StdEntity.
     *
     * @return string
     *
     * @throws InvalidEntityException Entidade não é um objeto instanciado
     */
    final protected function getEntity()
    {
        $entity = $this->getNewEntityObject();
        if (!is_object($entity)) {
            throw $this->createInvalidEntityException();
        }

        return get_class($entity);
    }

    /**
     * Retorna namespace da classe de entidade.
     *
     * @return string
     *
     * @throws InvalidEntityException Entidade não é um objeto instanciado
     */
    final protected function getEntityNamespace()
    {
        $entity_namespace = explode('\\', $this->getEntity());
        array_pop($entity_namespace);

        return '\\'.implode('\\', $entity_namespace);
    }

    /**
     * Retorna nome da classe de entidade.
     *
     * @return string
     *
     * @throws InvalidEntityException Entidade não é um objeto instanciado
     */
    final protected function getEntityName()
    {
        $entity = explode('\\', $this->getEntity());

        return end($entity);
    }

    /**
     * Retorna objeto QueryBuilder(ORM) para busca dos dados da indexAction.
     *
     * @param Request $request Objeto HTTP Request do indexAction
     *
     * @return QueryBuilder
     */
    protected function listQueryBuilder(Request &$request)
    {
        return $this->getDoctrineManager()->createQueryBuilder()
            ->select('e')
            ->from($this->getEntity(), 'e');
    }

    /**
     * Retorna endereço da view a ser renderizada.
     *
     * @param string $action Nome da action(indexAction, addAction ou editAction)
     *
     * @return string
     */
    abstract protected function getViewAddress($action);

    /**
     * Retorna formulário de inserção.
     *
     * @param object $entity Objeto de entidade para formulário de inserção
     *
     * @return Form
     *
     * @throws InvalidEntityException Entidade não é um objeto instanciado
     */
    protected function createCreateForm($entity)
    {
        if (!is_object($entity)) {
            throw $this->createInvalidEntityException();
        }
        $route = $this->isIndexCrud()
            ? $this->getRoute('indexAction')
            : $this->getRoute('createAction');
        $form = $this->createForm($this->getFormType(), $entity, array(
            'action' => $this->generateUrl($route),
            'method' => 'POST',
        ));
        $form->add('submit', 'submit');

        return $form;
    }

    /**
     * Método responsável por adicionar novos registros.
     *
     * @param Request $request     HTTP Request method
     * @param string  $action_name Nome do método de action
     *
     * @return array
     */
    private function createData(Request &$request, $action_name)
    {
        $entity = $this->getNewEntityObject();
        $form = $this->createCreateForm($entity);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrineManager();
                $em->persist($entity);
                $em->flush();
                $this->get('session')
                    ->getFlashBag()
                    ->add('success', 'Operação realizada com sucesso.');

                return $this->redirect($this->generateUrl($this->getRedirectRoute($action_name, false)));
            } else {
                $this->get('session')
                    ->getFlashBag()
                    ->add('danger', 'Falha ao realizar operação.');
                $redirect_route = $this->getRedirectRoute($action_name, true);
                if ($redirect_route !== null) {
                    return $this->redirect($this->generateUrl($redirect_route));
                }
            }
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Retorna formulário de alteração.
     *
     * @param object $entity Objeto de entidade para formulário de alteração
     *
     * @return Form
     *
     * @throws InvalidEntityException Entidade não é um objeto instanciado
     */
    protected function createEditForm($entity)
    {
        if (!is_object($entity)) {
            throw $this->createInvalidEntityException();
        }
        $route_url = $this->isIndexCrud()
            ? $this->getRoute('indexAction')
            : $this->getRoute('addAction');
        $form = $this->createForm($this->getFormType(), $entity, array(
            'action' => $this->generateUrl($route_url, array(
                'id' => $entity->getId(),
            )),
            'method' => 'PUT',
        ));
        $form->add('submit', 'submit');

        return $form;
    }

    /**
     * Método responsável por alterar registros.
     *
     * @param Request $request     HTTP Request method
     * @param string  $action_name Nome do método de action
     * @param int     $id          Identificação do registro
     *
     * @return array
     */
    protected function editData(Request &$request, $action_name, $id)
    {
        $em = $this->getDoctrineManager();
        $entity = $em->getRepository($this->getEntity())->find($id);
        if (!$entity) {
            $this->get('session')
                ->getFlashBag()
                ->add('danger', 'Registro não encontrado.');

            return $this->redirect($this->generateUrl($this->getRedirectRoute($action_name, false)));
        }
        $form = $this->createEditForm($entity);
        if ($request->isMethod('PUT')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em->persist($entity);
                $em->flush();
                $this->get('session')
                    ->getFlashBag()
                    ->add('success', 'Operação realizada com sucesso.');

                return $this->redirect($this->generateUrl($this->getRedirectRoute($action_name, false)));
            } else {
                $this->get('session')
                    ->getFlashBag()
                    ->add('danger', 'Falha ao realizar operação.');
                $redirect_route = $this->getRedirectRoute($action_name, true);
                if ($redirect_route !== null) {
                    return $this->redirect($this->generateUrl($redirect_route));
                }
            }
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    public function indexAction(Request $request, $id = null)
    {
        if (!$this->isIndexActionAuthorized()) {
            throw $this->createAccessDeniedException();
        }
        $entity_q = $this->listQueryBuilder($request);
        if (!$request->query->get('sort')) {
            $entity_q->orderBy("e.{$this->defaultSort()}", 'DESC');
        }
        $page = $request->query->get('page')
            ? $request->query->get('page')
            : 1;
        $limit = $request->query->get('limit')
            ? $request->query->get('limit')
            : 10;
        $entities = $this->isDataPagination()
            ? $this->get('knp_paginator')->paginate($entity_q->getQuery(), $page, $limit)
            : $entity_q->getQuery()->getResult();
        $view_data = array(
            'entities' => $entities,
        );
        if ($this->isIndexCrud()) {
            $crud = ($id !== null)
                ? $this->editData($request, $this->getActionName(__METHOD__), $id)
                : $this->createData($request, $this->getActionName(__METHOD__));
            if (!is_array($crud)) {
                return $crud;
            }
            $view_data = array_merge($view_data, $crud);
        }

        return $this->render($this->getViewAddress($this->getActionName(__METHOD__)), $view_data);
    }

    public function showAction($id)
    {
        if (!$this->isDetailsActionAuthorized()) {
            throw $this->createAccessDeniedException();
        }
        $em = $this->getDoctrineManager();
        $entity = $em->getRepository($this->getBundleName().':'.$this->getEntityName())->find($id);
        if (!$entity) {
            $this->get('session')
                ->getFlashBag()
                ->add('danger', 'Registro não encontrado.');
            $redirect_route = $this->getRedirectRoute($this->getActionName(__METHOD__), true);
            if ($redirect_route !== null) {
                return $this->redirect($this->generateUrl($redirect_route));
            }
        }

        return $this->render($this->getViewAddress($this->getActionName(__METHOD__)), array(
            'entity' => $entity,
        ));
    }

    public function createAction(Request $request)
    {
        if (!$this->isAddActionAuthorized()) {
            throw $this->createAccessDeniedException();
        }
        $crud = $this->createData($request, $this->getActionName(__METHOD__));
        if (!is_array($crud)) {
            return $crud;
        }

        return $this->render($this->getViewAddress($this->getActionName(__METHOD__)), $crud);
    }

    public function editAction(Request $request, $id)
    {
        if (!$this->isEditActionAuthorized()) {
            throw $this->createAccessDeniedException();
        }
        $crud = $this->editData($request, $this->getActionName(__METHOD__), $id);
        if (!is_array($crud)) {
            return $crud;
        }

        return $this->render($this->getViewAddress($this->getActionName(__METHOD__)), $crud);
    }

    public function removeAction($id)
    {
        if (!$this->isRemoveActionAuthorized()) {
            throw $this->createAccessDeniedException();
        }
        $em = $this->getDoctrineManager();
        $entity = $em->getRepository($this->getEntityNamespace().'\\'.$this->getEntityName())->find($id);
        if (!$entity) {
            $this->get('session')
                ->getFlashBag()
                ->add('danger', 'Registro não encontrado.');
            $redirect_route = $this->getRedirectRoute($this->getActionName(__METHOD__), true);
            if ($redirect_route !== null) {
                return $this->redirect($this->generateUrl($redirect_route));
            }
        } else {
            $em->remove($entity);
            $em->flush();
            $this->get('session')
                ->getFlashBag()
                ->add('success', 'Operação realizada com sucesso.');
        }

        return $this->redirect($this->generateUrl($this->getRedirectRoute($this->getActionName(__METHOD__), false)));
    }
}
