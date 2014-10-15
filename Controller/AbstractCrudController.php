<?php

namespace Mero\BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Mero\BaseBundle\Entity\AbstractEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FormType;

/**
 * Classe abstrata para criação de CRUD simples
 *
 * @package Mero\BaseBundle\Controller
 * @author Rafael Mello <merorafael@gmail.com>
 * @copyright Copyright (c) 2014 - Rafael Mello
 * @license https://github.com/merorafael/MeroBaseBundle/blob/master/LICENSE BSD license
 */
abstract class AbstractCrudController extends Controller
{
    
    /**
     * Nome da rota para indexAction
     *
     * @var string
     */
    const indexRoute = 'index';
    
    /**
     * Nome da rota para addAction
     *
     * @var string
     */
    const addRoute = 'add';
    
    /**
     * Nome da rota para editAction
     *
     * @var string
     */
    const editRoute = 'edit';
    
    /**
     * Nome da rota para redirecionamento pós-inserção.
     * 
     * @var string
     */
    const createdRoute = null;
    
    /**
     * Nome da rota para redirecionamento pós-atualização.
     *
     * @var string
     */
    const updatedRoute = null;
    
    /**
     * Nome da rota para redirecionamento pós-exclusão.
     *
     * @var string
     */
    const removedRoute = null;
    
    /**
     * Namespace referente a classe da entidade.
     *
     * @return string
     */
    abstract protected function getEntityNamespace();
    
    /**
     * Classe referente a entidade.
     * 
     * @return string
     */
    abstract protected function getEntityName();
    
    /**
     * Nome referente ao bundle.
     * 
     * @return string
     */
    abstract protected function getBundleName();
    
    /**
     * Nome referente ao tipo de formulario
     * 
     * @return string
     */
    abstract protected function getType();
    
    /**
     * Alias de $this->getDoctrine()->getEntityManager()
     * 
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEm()
    {
        return $this->getDoctrine()->getManager();
    }
    
    /**
     * Método utilizado em classes extendidas para manipular
     * o Query Builder padrão.
     * 
     * @param \Doctrine\ORM\QueryBuilder $entity_q
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function indexQueryBuilder(\Doctrine\ORM\QueryBuilder $entity_q)
    {
        return $entity_q;
    }
    
    /**
     * Retorna campo padrão utilizado para ordenação de dados.
     * 
     * @return string
     */
    protected function defaultSort()
    {
        return 'created';
    }

    /**
     * Método utilizado em classes extendidas para manipular dados
     * da entidade que não correspondem a um CRUD simples.
     * 
     * @param AbstractEntity $entity
     */
    protected function dataManager(AbstractEntity $entity) 
    {
        return $entity;
    }
    
    /**
     * Cria o formulário de inserção de dados baseado na entidade informada.
     * 
     * @param AbstractEntity $entity
     * @return \Symfony\Component\Form\Form
     */
    protected function getInsertForm(AbstractEntity $entity)
    {
        $form = $this->createForm($this->getType(), $entity, array(
            'action' => $this->generateUrl(static::addRoute),
            'method' => 'POST'
        ));
        $form->add('submit', 'submit');
        return $form;
    }
    
    /**
     * Cria o formulário de alteração de dados baseado na entidade informada.
     * 
     * @param AbstractEntity $entity
     * @return \Symfony\Component\Form\Form
     */
    protected function getUpdateForm(AbstractEntity $entity)
    {
        $form = $this->createForm($this->getType(), $entity, array(
            'action' => $this->generateUrl(static::editRoute, array(
                'id' => $entity->getId()
            )),
            'method' => 'PUT'
        ));
        $form->add('submit', 'submit');
        return $form;
    }
    
    /**
     * Action de listagem dos registros
     * 
     * Os dados exibidos são controlados com parâmetros $_GET
     * page - Qual página está sendo exibida(padrão 0);
     * limit - Quantidade de registros por página(padrão 10);
     * sort - Campo a ser utilizado para ordenação(padrão "created")
     * order - Como será ordernado o campo sort(padrão DESC)
     * 
     * @param Request $request
     * @param integer $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request, $id = null)
    {
        $page = $request->query->get('page') ? $request->query->get('page') : 1;
        $limit = $request->query->get('limit') ? $request->query->get('limit') : 10;
        
        $em = $this->getEm();
        $entity_q = $em->createQueryBuilder()
            ->select('e')
            ->from($this->getBundleName().":".$this->getEntityName(), 'e')
        ;
        
        $entity_q = $this->indexQueryBuilder($entity_q);
        
        //Recurso dependente do KnpPaginatorBundle
        $entities = $this->get('knp_paginator')->paginate($entity_q->getQuery(), $page, $limit);
        
        //Adiciona formulário de CRUD(adicionar ou editar de acordo com a identificação informada).
        $crud = !empty($id) ? $this->editData($request, $id) : $this->addData($request);
        if (!is_array($crud)) {
            return $crud;
        }
        
        return $this->render($this->getBundleName().":".$this->getEntityName().":index.html.twig", array_merge(
            $crud,
            array(
                'entities' => $entities
            )
        ));
    }
    
    /**
     * Action para exibir detalhes de registro especifico
     * 
     * @param integer $id
     */
    public function detailsAction($id)
    {
        $em = $this->getEm();
        $entity = $em->getRepository($this->getBundleName().":".$this->getEntityName())->find($id);
        if (!$entity) {
            $this->get('session')
                ->getFlashBag()
                ->add('danger', 'Registro não encontrado.');
            return $this->redirect($this->generateUrl(static::indexRoute));
        }
        return $this->render($this->getBundleName().":".$this->getEntityName().":details.html.twig", array(
            'entity' => $entity
        ));
    }
    
    /**
     * Método responsável por adicionar novos registros
     * 
     * @param Request $request
     * @return multitype:\Symfony\Component\Form\Form Ambigous <unknown, AbstractEntity>
     */
    private function addData(Request $request)
    {
        $entity_class = $this->getEntityNamespace()."\\".$this->getEntityName();
        if (!class_exists($entity_class)) {
            throw $this->createNotFoundException('Entity not found');
        }
        $entity = new $entity_class();
        $form = $this->getInsertForm($entity);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $entity = $this->dataManager($entity);
                $em = $this->getEm();
                $em->persist($entity);
                $em->flush();
                $this->get('session')
                    ->getFlashBag()
                    ->add('success', 'Operação realizada com sucesso.');
                return $this->redirect($this->generateUrl(is_null(static::createdRoute) ? static::indexRoute : static::createdRoute));
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
     * Action para adicionar novos registros
     * 
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        $crud  = $this->addData($request);
        if (!is_array($crud)) {
            return $crud;
        }
        return $this->render($this->getBundleName().":".$this->getEntityName().":add.html.twig", $crud);
    }
    
    /**
     * Método responsável por editar registros
     * 
     * @param Request $request
     * @param integer $id
     * @return multitype:NULL AbstractEntity
     */
    private function editData(Request $request, $id)
    {
        $em = $this->getEm();
        $entity = $em->getRepository($this->getBundleName().":".$this->getEntityName())->find($id);
        if (!$entity) {
            $this->get('session')
            ->getFlashBag()
            ->add('danger', 'Registro não encontrado.');
            return $this->redirect($this->generateUrl(is_null(static::updatedRoute) ? static::indexRoute : static::updatedRoute));
        }
        $form = $this->getUpdateForm($entity);
        if ($request->isMethod('PUT')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $entity = $this->dataManager($entity);
                $em->persist($entity);
                $em->flush();
                $this->get('session')
                ->getFlashBag()
                ->add('success', 'Operação realizada com sucesso.');
                return $this->redirect($this->generateUrl(is_null(static::updatedRoute) ? static::indexRoute : static::updatedRoute));
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
     * Action para editar registros
     * 
     * @param Request $request
     * @param integer $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $id)
    {
        $crud = $this->editData($request, $id);
        if (!is_array($crud)) {
            return $crud;
        }
        return $this->render($this->getBundleName().":".$this->getEntityName().":edit.html.twig", $crud);
    }
    
    /**
     * Action para remover registros
     * 
     * @param integer $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeAction($id)
    {
        $em = $this->getEm();
        $entity = $em->getRepository($this->getBundleName().":".$this->getEntityName())->find($id);
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
        return $this->redirect($this->generateUrl(is_null(static::removedRoute) ? static::indexRoute : static::removedRoute));
    }
}