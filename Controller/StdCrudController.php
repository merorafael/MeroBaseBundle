<?php

namespace Mero\BaseBundle\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Mero\BaseBundle\Entity\StdEntity;
use Mero\BaseBundle\Exception\InvalidEntityException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Abstract class for simple CRUD creation.
 *
 * @package Mero\BaseBundle\Controller
 * @author Rafael Mello <merorafael@gmail.com>
 * @Copyright Copyright (c) 2014~2015 - Rafael Mello
 * @license https://github.com/merorafael/MeroBaseBundle/blob/master/LICENSE MIT license
 */
abstract class StdCrudController extends StdController
{

    /**
     * Habilita CRUD no indexAction.
     *
     * @return bool
     */
    abstract protected function isIndexCrud();

    /**
     * Habilita paginação de dados.
     *
     * @return bool
     */
    abstract protected function isDataPagination();

    /**
     * Retorna nome da rota referente a action informada.
     *
     * @param string $action Nome da action(indexAction, addAction, editAction ou removeAction)
     *
     * @return string
     */
    abstract protected function getRoute($action);

    /**
     * Retorna rota de direcionamento pós-processamento.
     *
     * @param string $origin_action Página solicitante(indexAction, addAction, editAction ou removeAction)
     * @param bool $fail Identificador de falha ocorrida durante processamento
     *
     * @return null|string
     */
    abstract protected function getRedirectRoute($origin_action, $fail = false);

    /**
     * @return mixed Entidade referente ao CRUD
     */
    abstract protected function newEntityObject();

    /**
     * Retorna objeto relacionado ao Type do formulário.
     *
     * @return AbstractType Form object
     */
    abstract protected function getFormType();

    /**
     * Retorna nome da entidade incluindo namespace.
     *
     * Ex: Mero\BaseBundle\Entity\StdEntity
     *
     * @return string
     */
    protected final function getEntity()
    {
        return get_class($this->newEntityObject());
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * Return entity namespace.
     *
     * @return string
     */
    protected final function getEntityNamespace()
    {
        $entity_address = explode("\\", $this->getEntity());
        array_pop($entity_address);
        return "\\".implode("\\", $entity_address);
    }
    
    /**
     * Return entity name.
     * 
     * @return string
     */
    protected final function getEntityName()
    {
        $entity_address = explode("\\", $this->getEntity());
        return end($entity_address);
    }
    
    /**
     * Retorna nome da view a ser renderizado.
     * 
     * Por padrão o nome da view é o mesmo da entidade, caso
     * a controller não utilize esse padrão, sobrescreva este método. 
     * 
     * @return string Nome da view
     */
    protected function getViewName()
    {
        $check_prefix = strstr($this->getEntityNamespace(), "Entity\\");
        return ($check_prefix === true) ? str_replace("Entity\\", "", $check_prefix)."\\".$this->getEntityName() : $this->getEntityName();
    }
    
    /**
     * Retorna campo padrão utilizado para ordenação de dados.
     * 
     * @return string Campo da entity
     */
    protected function defaultSort()
    {
        return "created";
    }
    
    /**
     * @return QueryBuilder
     */
    protected function getIndexQuery()
    {
        $em = $this->getEntityManager();
        $entity_q = $em->createQueryBuilder()
            ->select("e")
            ->from($this->getEntityNamespace()."\\".$this->getEntityName(), "e");
        return $entity_q;
    }
    
    /**
     * Método utilizado em classes extendidas para manipular dados da entidade que não 
     * correspondem a um CRUD simples.
     * 
     * @param mixed $entity Entidade referente ao CRUD
     *
     * @return mixed
     */
    protected function dataManagerAdd($entity)
    {
        return $entity;
    }
    
    /**
     * Método utilizado em classes extendidas para manipular dados da entidade que não
     * correspondem a um CRUD simples.
     *
     * @param mixed $entity Entidade referente ao CRUD
     *
     * @return mixed
     */
    protected function dataManagerEdit($entity)
    {
        return $entity;
    }
    
    /**
     * Cria o formulário de inserção de dados baseado na entidade informada.
     * 
     * @param StdEntity $entity CRUD entity
     *
     * @throws InvalidEntityException The entity is not instantiated object
     *
     * @return Form
     */
    protected function getInsertForm($entity)
    {
        if (!is_object($entity)) {
            throw new InvalidEntityException("The entity is not instantiated object");
        }
        $route = $this->isIndexCrud() ? $this->getRoute("indexAction") : $this->getRoute("addAction");
        $form = $this->createForm($this->getFormType(), $entity, array(
            "action" => $this->generateUrl($route),
            "method" => "POST"
        ));
        $form->add("submit", "submit");
        return $form;
    }
    
    /**
     * Cria o formulário de alteração de dados baseado na entidade informada.
     * 
     * @param mixed $entity CRUD entity
     *
     * @throws InvalidEntityException The entity is not instantiated object
     *
     * @return Form
     */
    protected function getUpdateForm($entity)
    {
        if (!is_object($entity)) {
            throw new InvalidEntityException("The entity is not instantiated object");
        }
        $route = $this->isIndexCrud() ? $this->getRoute("indexAction") : $this->getRoute("editAction");
        $form = $this->createForm($this->getFormType(), $entity, array(
            "action" => $this->generateUrl($route, array(
                "id" => $entity->getId()
            )),
            "method" => "PUT"
        ));
        $form->add("submit", "submit");
        return $form;
    }
    
    /**
     * Método responsável por adicionar novos registros
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     */
    private function addData(Request $request)
    {
        $entity = $this->newEntityObject();
        $form = $this->getInsertForm($entity);
        if ($request->isMethod("POST")) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $entity = $this->dataManagerAdd($entity);;
                $em = $this->getEntityManager();
                $em->persist($entity);
                $em->flush();
                $this->get("session")
                    ->getFlashBag()
                    ->add("success", "Operação realizada com sucesso.");
                return $this->redirect($this->generateUrl($this->getRedirectRoute(__METHOD__, false)));
            } else {
                $this->get("session")
                    ->getFlashBag()
                    ->add("danger", "Falha ao realizar operação.");
            }
        }
        return array(
            "entity" => $entity,
            "form" => $form->createView()
        );
    }
    
    /**
     * Método responsável por alterar registros
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $id Identificação do registro
     * @return array
     */
    protected function editData(Request $request, $id)
    {
        $em = $this->getEntityManager();
        $entity = $em->getRepository($this->getEntityNamespace()."\\".$this->getEntityName())->find($id);
        if (!$entity) {
            $this->get("session")
            ->getFlashBag()
            ->add("danger", "Registro não encontrado.");
            return $this->redirect($this->generateUrl($this->getRedirectRoute(__METHOD__, true)));
        }
        $form = $this->getUpdateForm($entity);
        if ($request->isMethod("PUT")) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $entity = $this->dataManagerEdit($entity);
                $em->persist($entity);
                $em->flush();
                $this->get("session")
                    ->getFlashBag()
                    ->add("success", "Operação realizada com sucesso.");
                return $this->redirect($this->generateUrl($this->getRedirectRoute(__METHOD__, false)));
            } else {
                $this->get("session")
                    ->getFlashBag()
                    ->add("danger", "Falha ao realizar operação.");
            }
        }
        return array(
            "entity" => $entity,
            "form" => $form->createView()
        );
    }
    
    /**
     * Action de listagem dos registros.
     * 
     * @param Request $request
     * @param int $id Utilizado para editar um registro na indexAction caso informado
     *
     * @return Response
     */
    public function indexAction(Request $request, $id)
    {
        $page = $request->query->get("page") ? $request->query->get("page") : 1;
        $limit = $request->query->get("limit") ? $request->query->get("limit") : 10;

        $entity_q = $this->getIndexQuery();
        if (!$request->query->get("sort")) {
            $entity_q->orderBy("e.{$this->defaultSort()}", "DESC");
        }
        $entities = $this->isDataPagination() ? $this->get("knp_paginator")->paginate($entity_q->getQuery(), $page, $limit) : $entity_q->getQuery()->getResult();
        $view_data = array(
            "entities" => $entities
        );
        if ($this->isIndexCrud()) {
            $crud = !empty($id) ? $this->editData($request, $id) : $this->addData($request);
            if (!is_array($crud)) {
                return $crud;
            }
            $view_data = array_merge($view_data, $crud);
        }
        return $this->render($this->getBundleName().":".$this->getViewName().":index.html.twig", $view_data);
    }
    
    /**
     * Action para exibir detalhes de registro especifico
     * 
     * @param int $id Identificação do registro
     *
     * @return Response
     */
    public function detailsAction($id)
    {
        $em = $this->getEntityManager();
        $entity = $em->getRepository($this->getBundleName().":".$this->getEntityName())->find($id);
        if (!$entity) {
            $this->get("session")
                ->getFlashBag()
                ->add("danger", "Registro não encontrado.");
            return $this->redirect($this->generateUrl($this->getRoute("indexAction")));
        }
        return $this->render($this->getBundleName().":".$this->getViewName().":details.html.twig", array(
            "entity" => $entity
        ));
    }
    
    /**
     * Action para adicionar novos registros
     * 
     * @param Request $request
     * 
     * @return RedirectResponse|Response
     */
    public function addAction(Request $request)
    {
        $crud = $this->addData($request);
        if (!is_array($crud)) {
            return $crud;
        }
        return $this->render($this->getBundleName().":".$this->getViewName().":add.html.twig", $crud);
    }
    
    /**
     * Método action responsável por alteração de registros
     * 
     * @param Request $request
     * @param int $id Identificação do registro
     * 
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, $id)
    {
        $crud = $this->editData($request, $id);
        if (!is_array($crud)) {
            return $crud;
        }
        return $this->render($this->getBundleName().":".$this->getViewName().":edit.html.twig", $crud);
    }
    
    /**
     * Método action responsável por remoção de registros
     * 
     * @param int $id Identificação do registro
     * 
     * @return RedirectResponse
     */
    public function removeAction($id)
    {
        $em = $this->getEntityManager();
        $entity = $em->getRepository($this->getEntityNamespace()."\\".$this->getEntityName())->find($id);
        if (!$entity) {
            $this->get("session")
                ->getFlashBag()
                ->add("danger", "Registro não encontrado.");
        } else {
            $em->remove($entity);
            $em->flush();
            $this->get("session")
                ->getFlashBag()
                ->add("success", "Operação realizada com sucesso.");
        }
        return $this->redirect($this->generateUrl($this->getRedirectRoute(__METHOD__, false)));
    }
}
