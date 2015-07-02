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
    protected function getFilterForm()
    {
        return null;
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
     * Retorna nome da entidade incluindo namespace. Ex: Mero\Bundle\BaseBundle\Entity\StdEntity
     *
     * @return string
     *
     * @throws InvalidEntityException Entidade não é um objeto instanciado
     */
    protected final function getEntity()
    {
        $entity_object = $this->getNewEntityObject();
        if (!is_object($entity_object)) {
            throw new InvalidEntityException("The entity is not instantiated object");
        }
        return get_class($entity_object);
    }

    /**
     * Retorna namespace da classe de entidade.
     *
     * @return string
     *
     * @throws InvalidEntityException Entidade não é um objeto instanciado
     */
    protected final function getEntityNamespace()
    {
        $entity_namespace = explode("\\", $this->getEntity());
        array_pop($entity_namespace);
        return "\\".implode("\\", $entity_namespace);
    }

    /**
     * Retorna nome da classe de entidade.
     *
     * @return string
     *
     * @throws InvalidEntityException Entidade não é um objeto instanciado
     */
    protected final function getEntityName()
    {
        $entity = explode("\\", $this->getEntity());
        return end($entity);
    }

    /**
     * Retorna formulário de inserção.
     *
     * @param object $entity Objeto de entidade para formulário de inserção
     *
     * @return Form
     *
     * @throws InvalidEntityException Entidade não é um objeto instanciado
     */
    protected function getInsertForm($entity)
    {
        if (!is_object($entity)) {
            throw new InvalidEntityException("The entity is not instantiated object");
        }
        $route = $this->isIndexCrud()
            ? $this->getRoute("indexAction")
            : $this->getRoute("addAction");
        $form = $this->createForm($this->getFormType(), $entity, array(
            "action" => $this->generateUrl($route),
            "method" => "POST"
        ));
        $form->add("submit", "submit");
        return $form;
    }

    /**
     * Retorna formulário de alteração.
     *
     * @param object $entity Objeto de entidade para formulário de alteração
     * @param mixed $entity_id ID do registro em alteração
     *
     * @return Form
     *
     * @throws InvalidEntityException Entidade não é um objeto instanciado
     */
    protected function getUpdateForm($entity, $entity_id)
    {
        if (!is_object($entity)) {
            throw new InvalidEntityException("The entity is not instantiated object");
        }
        $route = $this->isIndexCrud()
            ? $this->getRoute("indexAction")
            : $this->getRoute("editAction");
        $form = $this->createForm($this->getFormType(), $entity, array(
            "action" => $this->generateUrl($route, array(
                "id" => $entity_id
            )),
            "method" => "PUT"
        ));
        $form->add("submit", "submit");
        return $form;
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
        return $this->getEntityManager()->createQueryBuilder()
            ->select("e")
            ->from($this->getEntity(), "e");
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
        return ($check_prefix)
            ? str_replace("Entity\\", "", $check_prefix)."\\".$this->getEntityName()
            : $this->getEntityName();
    }

    /**
     * Método responsável por adicionar novos registros.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    private function addData(Request $request)
    {
        $entity = $this->getNewEntityObject();
        $form = $this->getInsertForm($entity);
        if ($request->isMethod("POST")) {
            $form->handleRequest($request);
            if ($form->isValid()) {
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
                $redirect_route = $this->getRedirectRoute(__METHOD__, true);
                if ($redirect_route !== null) {
                    return $this->redirect($this->generateUrl($redirect_route));
                }
            }
        }
        return array(
            "entity" => $entity,
            "form" => $form->createView()
        );
    }

    /**
     * Método responsável por alterar registros.
     *
     * @param Request $request
     * @param int $id Identificação do registro
     *
     * @return array
     */
    protected function editData(Request $request, $id)
    {
        $em = $this->getEntityManager();
        $entity = $em->getRepository($this->getEntity())->find($id);
        if (!$entity) {
            $this->get("session")
                ->getFlashBag()
                ->add("danger", "Registro não encontrado.");
            return $this->redirect($this->generateUrl($this->getRedirectRoute(__METHOD__, true)));
        }
        $form = $this->getUpdateForm($entity, $entity->getId());
        if ($request->isMethod("PUT")) {
            $form->handleRequest($request);
            if ($form->isValid()) {
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
                $redirect_route = $this->getRedirectRoute(__METHOD__, true);
                if ($redirect_route !== null) {
                    return $this->redirect($this->generateUrl($redirect_route));
                }
            }
        }
        return array(
            "entity" => $entity,
            "form" => $form->createView()
        );
    }

    public function indexAction(Request $request, $id = null)
    {
        if (!$this->isIndexActionAuthorized()) {
            throw $this->createAccessDeniedException();
        }
        $entity_q = $this->listQueryBuilder($request);
        if (!$request->query->get("sort")) {
            $entity_q->orderBy("e.{$this->defaultSort()}", "DESC");
        }
        $page = $request->query->get("page")
            ? $request->query->get("page")
            : 1;
        $limit = $request->query->get("limit")
            ? $request->query->get("limit")
            : 10;
        $entities = $this->isDataPagination()
            ? $this->get("knp_paginator")->paginate($entity_q->getQuery(), $page, $limit)
            : $entity_q->getQuery()->getResult();
        $view_data = array(
            "entities" => $entities
        );
        if ($this->isIndexCrud()) {
            $crud = !empty($id)
                ? $this->editData($request, $id)
                : $this->addData($request);
            if (!is_array($crud)) {
                return $crud;
            }
            $view_data = array_merge($view_data, $crud);
        }
        return $this->render($this->getBundleName().":".$this->getViewName().":index.html.twig", $view_data);
    }

    public function detailsAction($id)
    {
        if (!$this->isDetailsActionAuthorized()) {
            throw $this->createAccessDeniedException();
        }
        $em = $this->getEntityManager();
        $entity = $em->getRepository($this->getBundleName().":".$this->getEntityName())->find($id);
        if (!$entity) {
            $this->get("session")
                ->getFlashBag()
                ->add("danger", "Registro não encontrado.");
            return $this->redirect($this->generateUrl($this->getRedirectRoute(__METHOD__, true)));
        }
        return $this->render($this->getBundleName().":".$this->getViewName().":details.html.twig", array(
            "entity" => $entity
        ));
    }

    public function addAction(Request $request)
    {
        if (!$this->isAddActionAuthorized()) {
            throw $this->createAccessDeniedException();
        }
        $crud = $this->addData($request);
        if (!is_array($crud)) {
            return $crud;
        }
        return $this->render($this->getBundleName().":".$this->getViewName().":add.html.twig", $crud);
    }

    public function editAction(Request $request, $id)
    {
        if (!$this->isEditActionAuthorized()) {
            throw $this->createAccessDeniedException();
        }
        $crud = $this->editData($request, $id);
        if (!is_array($crud)) {
            return $crud;
        }
        return $this->render($this->getBundleName().":".$this->getViewName().":edit.html.twig", $crud);
    }

    public function removeAction($id)
    {
        if (!$this->isRemoveActionAuthorized()) {
            throw $this->createAccessDeniedException();
        }
        $em = $this->getEntityManager();
        $entity = $em->getRepository($this->getEntityNamespace()."\\".$this->getEntityName())->find($id);
        if (!$entity) {
            $this->get("session")
                ->getFlashBag()
                ->add("danger", "Registro não encontrado.");
            $redirect_route = $this->getRedirectRoute(__METHOD__, true);
            if ($redirect_route !== null) {
                return $this->redirect($this->generateUrl($redirect_route));
            }
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
