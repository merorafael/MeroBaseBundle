<?php
namespace Mero\Bundle\BaseBundle\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class StdCrudController
 *
 * @package Mero\Bundle\BaseBundle\Controller
 * @author Rafael Mello <merorafael@gmail.com>
 * @Copyright Copyright (c) 2014~2015 - Rafael Mello
 * @license https://github.com/merorafael/MeroBaseBundle/blob/master/LICENSE MIT license
 */
abstract class StdCrudController extends StdController
{

    /**
     * Retorna gerenciador de entidades do Doctrine.
     *
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * Retorna campo usado por padrão para ordenação dos dados na indexAction.
     *
     * @return string
     */
    protected function defaultSort()
    {
        return $this->getParameter("mero_base.default_sort");
    }

    /**
     * Verificador de CRUD na indexAction.
     *
     * @return bool
     */
    protected function isIndexCrud()
    {
        return $this->getParameter("mero_base.index_crud");
    }

    /**
     * Verificador de conteúdo paginado no indexAction.
     *
     * @return bool
     */
    protected function isDataPagination()
    {
        return $this->getParameter("mero_base.data_pagination");
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
     * Retorna formulário de filtro, caso exista.
     *
     * @return null|Form
     */
    abstract protected function getFilterForm();

    /**
     * Retorna objeto QueryBuilder(ORM) para busca dos dados da indexAction.
     *
     * @return QueryBuilder
     */
    protected function listQueryBuilder()
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select("e")
            ->from("", "e");
    }

    public function indexAction(Request $request, $id = null)
    {
        if (!$this->isIndexActionAuthorized()) {
            throw $this->createAccessDeniedException();
        }
        $entity_q = $this->listQueryBuilder();
        if (!$request->query->get("sort")) {
            $entity_q->orderBy("e.{$this->defaultSort()}", "DESC");
        }
        $page = $request->query->get("page")
            ? $request->query->get("page")
            : 1;
        $limit = $request->query->get("limit")
            ? $request->query->get("limit")
            : 10;
        var_dump($this->getBundleName());
        //$entities = $this->isDataPagination() ? $this->get("knp_paginator")->paginate($entity_q->getQuery(), $page, $limit) : $entity_q->getQuery()->getResult();
    }

    public function detailsAction()
    {
        if (!$this->isDetailsActionAuthorized()) {
            throw $this->createAccessDeniedException();
        }
    }

    public function addAction()
    {
        if (!$this->isAddActionAuthorized()) {
            throw $this->createAccessDeniedException();
        }
    }

    public function editAction()
    {
        if (!$this->isEditActionAuthorized()) {
            throw $this->createAccessDeniedException();
        }
    }

    public function removeAction()
    {
        if (!$this->isRemoveActionAuthorized()) {
            throw $this->createAccessDeniedException();
        }
    }

}
