<?php

namespace Mero\BaseBundle\Controller;

use Doctrine\ORM\QueryBuilder;
use Mero\BaseBundle\Entity\AbstractEntity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Classe abstrata para criação de CRUD simples
 *
 * @package Mero\BaseBundle\Controller
 * @author Rafael Mello <merorafael@gmail.com>
 * @link https://github.com/merorafael/MeroBaseBundle Repositório do projeto
 * @link http://merorafael.wordpress.com Blog pessoal
 * @Copyright Copyright (c) 2014~2015 - Rafael Mello
 * @license https://github.com/merorafael/MeroBaseBundle/blob/master/LICENSE MIT license
 */
abstract class AbstractCrudController extends Controller
{

    const INDEX_CRUD = true;

    const DATA_PAGINATION = true;

    const CREATED_ROUTE = null;

    const UPDATED_ROUTE = null;

    const REMOVED_ROUTE = null;

    /**
     * Retorna namespace relacionada a entidade.
     * Sobreescreva este método caso o namespace seja diferente do padrão.
     * 
     * Namespace padrão: \<Namespace do bundle>\Entity
     *
     * @return string Namespace da entidade
     */
    protected function getEntityNamespace()
    {
        return '\\'.str_replace('\Controller', '\Entity', substr(get_class($this), 0, strrpos(get_class($this), '\\')));
    }
    
    /**
     * Retorna nome da classe referente a entidade.
     * 
     * @return string Nome da entidade
     */
    protected function getEntityName()
    {
        return str_replace("\\", "", strrchr(str_replace("Controller", "", get_class($this)), "\\"));
    }
    
    /**
     * Retorna objeto relacionado ao Type do formulário.
     * 
     * @return \Symfony\Component\Form\AbstractType Objeto do tipo do formulário
     */
    protected function getFormType()
    {
        $type_class = str_replace("\Entity", "\Form", $this->getEntityNamespace())."\\".$this->getEntityName()."Type";
        if (!class_exists($type_class)) {
            throw $this->createNotFoundException('FormType not found');
        }
        return new $type_class;
    }
    
    /**
     * Retorna nome referente ao bundle.
     * 
     * @return string Nome do bundle
     */
    protected function getBundleName()
    {
        $this->getRoutePrefix();
        $namespace_explode = explode("\\", get_class($this));
        (String) $bundle_name = '';
        foreach ($namespace_explode as $value) {
            $find_bundlekey = strpos($value, "Bundle");
            if (($find_bundlekey == 0) && is_int($find_bundlekey)) {
                continue;
            }
            $bundle_name = $bundle_name.$value;
            if (($find_bundlekey != 0) && is_int($find_bundlekey)) {
                break;
            }
        }
        return $bundle_name;
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
        return ($check_prefix !== false) ? str_replace("Entity\\", "", $check_prefix)."\\".$this->getEntityName() : $this->getEntityName();
    }
    
    /**
     * Retorna prefixo a ser usado para a rota.
     * 
     * Reescreva este método caso o prefixo da rota não seja o utilizado
     * como padrão.
     * 
     * @return string Prefixo para a rota
     */
    protected function getRoutePrefix()
    {
        $request = $this->getRequest();
        $request_route = $request->attributes->get('_route');
        $route_prefix = str_replace(strrchr($request_route, '_'), '', $request_route);
        return $route_prefix;
    }
    
    /**
     * Retorna nome da rota referente a action desejada.
     * 
     * @param string $action Nome da action
     * @return string Nome da rota
     */
    protected function getActionRoute($action = null)
    {
        return $this->getRoutePrefix()."_".$action;
    }
    
    /**
     * Retorna gerenciador de entidades(Entity Manager) do Doctrine.
     *
     * @return \Doctrine\ORM\EntityManager Entity Manager do Doctrine
     */
    protected function getEm()
    {
        return $this->getDoctrine()->getManager();
    }
    
    /**
     * Retorna campo padrão utilizado para ordenação de dados.
     * 
     * @return string Campo da entity
     */
    protected function defaultSort()
    {
        return 'created';
    }
    
    /**
     * Método utilizado em classes extendidas para alterar Query Builder padrão
     * utilizado pelo método indexAction.
     * 
     * @see http://doctrine-orm.readthedocs.org/en/latest/reference/query-builder.html Documentação do Query Builder pelo Doctrine
     * @see \Mero\BaseBundle\Controller::indexAction() Action referente a index do CRUD
     * 
     * @param \Doctrine\ORM\QueryBuilder $entity_q Entrada do Query Builder em indexAction
     * @return \Doctrine\ORM\QueryBuilder Query Builder processado pelo método
     */
    protected function indexQueryBuilder(QueryBuilder $entity_q)
    {
        return $entity_q;
    }

    /**
     * Chamado no momento em que uma nova entidade é criada.
     * 
     * @param AbstractEntity $entity Entidade referente ao CRUD
     * @return AbstractEntity Entidade referente ao CRUD
     */
    protected function newEntity(AbstractEntity $entity)
    {
        return $entity;
    }
    
    /**
     * Método utilizado em classes extendidas para manipular dados da entidade que não 
     * correspondem a um CRUD simples.
     * 
     * @param \Mero\BaseBundle\Entity\AbstractEntity $entity Entidade referente ao CRUD
     */
    protected function dataManager(AbstractEntity $entity) 
    {
        return $entity;
    }
    
    /**
     * Método utilizado em classes extendidas para manipular dados da entidade que não 
     * correspondem a um CRUD simples.
     * 
     * @param \Mero\BaseBundle\Entity\AbstractEntity $entity Entidade referente ao CRUD
     * @return \Mero\BaseBundle\Entity\AbstractEntity
     */
    protected function dataManagerAdd(AbstractEntity $entity) 
    {
        return $this->dataManager($entity);
    }
    
    /**
     * Método utilizado em classes extendidas para manipular dados da entidade que não
     * correspondem a um CRUD simples.
     *
     * @param \Mero\BaseBundle\Entity\AbstractEntity $entity Entidade referente ao CRUD
     * @return \Mero\BaseBundle\Entity\AbstractEntity
     */
    protected function dataManagerEdit(AbstractEntity $entity) 
    {
        return $this->dataManager($entity);
    }
    
    /**
     * Cria o formulário de inserção de dados baseado na entidade informada.
     * 
     * @param \Mero\BaseBundle\Entity\AbstractEntity $entity Entidade referente ao CRUD
     * @return \Symfony\Component\Form\Form Formulário do Symfony
     */
    protected function getInsertForm(AbstractEntity $entity)
    {
        $route = (static::INDEX_CRUD) ? $this->getActionRoute('index') :  $this->getActionRoute('add');
        $form = $this->createForm($this->getFormType(), $entity, array(
            'action' => $this->generateUrl($route),
            'method' => 'POST'
        ));
        $form->add('submit', 'submit');
        return $form;
    }
    
    /**
     * Cria o formulário de alteração de dados baseado na entidade informada.
     * 
     * @param \Mero\BaseBundle\Entity\AbstractEntity $entity Entity referente ao CRUD
     * @return \Symfony\Component\Form\Form Formulário do Symfony
     */
    protected function getUpdateForm(AbstractEntity $entity)
    {
        $route = strstr($this->getRequest()->attributes->get('_controller'), 'indexAction') ? $this->getActionRoute('index') :  $this->getActionRoute('edit');
        $form = $this->createForm($this->getFormType(), $entity, array(
            'action' => $this->generateUrl($route, array(
                'id' => $entity->getId()
            )),
            'method' => 'PUT'
        ));
        $form->add('submit', 'submit');
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
        $entity_class = $this->getEntityNamespace()."\\".$this->getEntityName();
        if (!class_exists($entity_class)) {
            throw $this->createNotFoundException('Entity not found');
        }
        $entity = $this->newEntity(new $entity_class());
        $form = $this->getInsertForm($entity);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $entity = $this->dataManagerAdd($entity);;
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();
                $this->get('session')
                    ->getFlashBag()
                    ->add('success', 'Operação realizada com sucesso.');
                return $this->redirect($this->generateUrl(is_null(static::CREATED_ROUTE) ? $this->getActionRoute('index') : static::CREATED_ROUTE));
            } else {
                $this->get('session')
                    ->getFlashBag()
                    ->add('danger', 'Falha ao realizar operação.');
            }
        }
        return array(
            'entity' => $entity,
            'form' => $form->createView()
        );
    }
    
    /**
     * Método responsável por alterar registros
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param integer $id Identificação do registro
     * @return array
     */
    private function editData(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository($this->getEntityNamespace()."\\".$this->getEntityName())->find($id);
        if (!$entity) {
            $this->get('session')
            ->getFlashBag()
            ->add('danger', 'Registro não encontrado.');
            return $this->redirect($this->generateUrl(is_null(static::UPDATED_ROUTE) ? $this->getActionRoute('index') : static::UPDATED_ROUTE));
        }
        $form = $this->getUpdateForm($entity);
        if ($request->isMethod('PUT')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $entity = $this->dataManagerEdit($entity);
                $em->persist($entity);
                $em->flush();
                $this->get('session')
                    ->getFlashBag()
                    ->add('success', 'Operação realizada com sucesso.');
                return $this->redirect($this->generateUrl(is_null(static::UPDATED_ROUTE) ? $this->getActionRoute('index') : static::UPDATED_ROUTE));
            } else {
                $this->get('session')
                    ->getFlashBag()
                    ->add('danger', 'Falha ao realizar operação.');
            }
        }
        return array(
            'entity' => $entity,
            'form' => $form->createView()
        );
    }
    
    /**
     * Action de listagem dos registros
     * 
     * Os dados exibidos são controlados com parâmetros $_GET
     * page - Qual página está sendo exibida(padrão 0);
     * limit - Quantidade de registros por página(padrão 10)
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param integer $id Utilizado para editar um registro na indexAction caso informado
     * @return \Symfony\Component\HttpFoundation\Response
     * 
     * @Route("/{id}", defaults={"id": null}, requirements={"id": "\d+"})
     */
    public function indexAction(Request $request, $id)
    {
        $page = $request->query->get('page') ? $request->query->get('page') : 1;
        $limit = $request->query->get('limit') ? $request->query->get('limit') : 10;
        
        $em = $this->getDoctrine()->getManager();
        $entity_q = $em->createQueryBuilder()
            ->select('e')
            ->from($this->getEntityNamespace()."\\".$this->getEntityName(), 'e');
        if (!$request->query->get('sort')) {
            $entity_q->orderBy("e.{$this->defaultSort()}", "DESC");
        }
        $entity_q = $this->indexQueryBuilder($entity_q);
        $entities = (static::DATA_PAGINATION === true) ? $this->get('knp_paginator')->paginate($entity_q->getQuery(), $page, $limit) : $entity_q->getQuery()->getResult();
        $view_data = array(
            'entities' => $entities
        );
        if (static::INDEX_CRUD === true) {
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
     * @param integer $id Identificação do registro
     * 
     * @Route("/detalhes/{id}", requirements={"id": "\d+"})
     */
    public function detailsAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository($this->getBundleName().":".$this->getEntityName())->find($id);
        if (!$entity) {
            $this->get('session')
                ->getFlashBag()
                ->add('danger', 'Registro não encontrado.');
            return $this->redirect($this->generateUrl($this->getActionRoute('index')));
        }
        return $this->render($this->getBundleName().":".$this->getViewName().":details.html.twig", array(
            'entity' => $entity
        ));
    }
    
    /**
     * Action para adicionar novos registros
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * 
     * @Route("/add")
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param integer $id Identificação do registro
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * 
     * @Route("/edit/{id}", requirements={"id": "\d+"})
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
     * @param integer $id Identificação do registro
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * 
     * @Route("/remove/{id}", requirements={"id": "\d+"})
     */
    public function removeAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository($this->getEntityNamespace()."\\".$this->getEntityName())->find($id);
        if (!$entity) {
            $this->get('session')
                ->getFlashBag()
                ->add('danger', 'Registro não encontrado.');
        } else {
            $em->remove($entity);
            $em->flush();
            $this->get('session')
                ->getFlashBag()
                ->add('success', 'Operação realizada com sucesso.');
        }
        return $this->redirect($this->generateUrl(is_null(static::REMOVED_ROUTE) ? $this->getActionRoute('index') : static::REMOVED_ROUTE));
    }
}
