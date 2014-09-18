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
     * Retorna classe referente ao tipo de formulário
     */
    abstract public function getType();
    
    /**
     * Retorna classe referente a entidade
     */
    abstract public function getEntityName();
    
    /**
     * Retorna nome do bundle
     */
    abstract public function getBundleName();
    
    /**
     * Retorna nome da rota informada.
     * 
     * @param string $action
     */
    abstract public function getRouteName($action);
    
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
        return $this->getDoctrine()->getEntityManager();
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
            'action' => $this->generateUrl($this->getAddRouteName()),
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
            'action' => $this->generateUrl($this->getEditRouteName(), array(
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
        $page = $request->query->get('page') ? $request->query->get('page') : 0;
        $limit = $request->query->get('limit') ? $request->query->get('limit') : 10;
        $sort = $request->query->get('sort') ? $request->query->get('sort') : $this->defaultSort();
        $order = $request->query->get('order') ? $request->query->get('order') : 'DESC';
        
        $em = $this->getEm();
        $entity_qb = $em->createQueryBuilder()
            ->select('e')
            ->from("{$this->getBundleName()}:{$this->getEntityName()}", 'e')
            ->orderBy('e.'.$sort, $order)
        ;
        $entities = $entity_qb
            ->getQuery()
            ->useResultCache(true, 120)
            ->getResult();
        
        
        $total_entities = count($entities);
        if ($page >= 0 && $limit > 0) {
            $max_page = ceil($total_entities / $limit);
            if ($page > $max_page)
                $page = $max_page;
            $start = $limit * $page - $limit;
            if ($start < 0) {
                $start = 0;
            }
            $entities = $entity_qb
                ->setFirstResult($start)
                ->setMaxResults($limit)
                ->getQuery()
                ->useResultCache(true, 120)
                ->getResult();
        }
        
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
        if (!class_exists($this->getEntityName())) {
            throw $this->createNotFoundException('Entity not found');
        }
        $entity_name = $this->getEntityName();
        $entity = new $entity_name();
        $form = $this->getInsertForm($entity);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getEm();
                $em->persist($entity);
                $em->flush();
                $this->get('session')
                    ->getFlashBag()
                    ->add('success', 'Operação realizada com sucesso.');
                return $this->redirect($this->generateUrl($this->getRouteName(__METHOD__)));
            } else {
                $this->get('danger')
                    ->getFlashBag()
                    ->add('success', 'Falha ao realizar operação.');
                return $this->redirect($this->generateUrl($this->getRouteName(__METHOD__)));
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
            return $this->redirect($this->generateUrl($this->getRouteName('indexAction')));
        }
        $form = $this->getUpdateForm($entity);
        if ($request->isMethod('PUT')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em->persist($entity);
                $em->flush();
                $this->get('session')
                    ->getFlashBag()
                    ->add('success', 'Operação realizada com sucesso.');
                return $this->redirect($this->generateUrl($this->getRouteName(__METHOD__)));
            } else {
                $this->get('session')
                    ->getFlashBag()
                    ->add('danger', 'Falha ao realizar operação.');
                return $this->redirect($this->generateUrl($this->getRouteName(__METHOD__)));
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
        return $this->redirect($this->generateUrl($this->getRouteName('indexAction')));
    }
}