<?php

namespace Mero\BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Mero\BaseBundle\Entity\AbstractEntity;
use Symfony\Component\HttpFoundation\Request;

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
     * Nome da rota para removeAction
     * 
     * @var string
     */
    const removeRoute = 'remove';
    
    /**
     * Retorna classe referente ao tipo de formulário
     */
    abstract public function getType();
    
    /**
     * Retorna namespace referente a entidade
     */
    abstract public function getEntityNamespace();
    
    /**
     * Retorna nome referente a entidade
     */
    abstract public function getEntityName();
    
    /**
     * Retorna nome do bundle
     */
    abstract public function getBundleName();

    /**
     * Método utilizado em classes extendidas para manipular dados
     * da entidade que não correspondem a um CRUD simples.
     * 
     * @param AbstractEntity $entity
     */
    protected function dataManager(AbstractEntity $entity) {
        return $entity;
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
     * Alias de $this->getDoctrine()->getEntityManager()
     * 
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEm()
    {
        return $this->getDoctrine()->getManager();
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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $page = $request->query->get('page') ? $request->query->get('page') : 1;
        $limit = $request->query->get('limit') ? $request->query->get('limit') : 10;
        
        $em = $this->getEm();
        $entity_q = $em->createQueryBuilder()
            ->select('e')
            ->from("{$this->getBundleName()}:{$this->getEntityName()}", 'e')
            ->getQuery()
        ;
        
        //Recurso dependente do KnpPaginatorBundle
        $entities = $this->get('knp_paginator')->paginate($entity_q, $page, $limit);
        
        return $this->render("{$this->getBundleName()}:{$this->getEntityName()}:index.html.twig", array(
            'entities' => $entities
        ));
    }
    
    /**
     * Action para adicionar novos registros
     * 
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        $entity_class = "{$this->getEntityNamespace()}\\{$this->getEntityName()}";
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
                return $this->redirect($this->generateUrl(static::indexRoute));
            } else {
                $this->get('session')
                    ->getFlashBag()
                    ->add('danger', 'Falha ao realizar operação.');
            }
        }
        return $this->render("{$this->getBundleName()}:{$this->getEntityName()}:add.html.twig", array(
            'entity' => $entity,
            'form' => $form->createView()
        ));
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
        $em = $this->getEm();
        $entity = $em->getRepository("{$this->getBundleName()}:{$this->getEntityName()}")->find($id);
        if (!$entity) {
            $this->get('session')
                ->getFlashBag()
                ->add('danger', 'Registro não encontrado.');
            return $this->redirect($this->generateUrl(static::indexRoute));
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
                return $this->redirect($this->generateUrl(static::indexRoute));
            } else {
                $this->get('session')
                    ->getFlashBag()
                    ->add('danger', 'Falha ao realizar operação.');
            }
        }
        return $this->render("{$this->getBundleName()}:{$this->getEntityName()}:edit.html.twig", array(
            'entity' => $entity,
            'form' => $form->createView()
        ));
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
        $entity = $em->getRepository("{$this->getBundleName()}:{$this->getEntityName()}")->find($id);
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
        return $this->redirect($this->generateUrl(static::indexRoute));
    }
}