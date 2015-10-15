<?php

namespace Mero\Bundle\BaseBundle\Controller\Action\Crud;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

trait IndexTrait
{

    /**
     * @return EntityManager Doctrine entity manager
     */
    abstract protected function getDoctrineManager();

    /**
     * @return string
     */
    abstract protected function getEntityName();

    /**
     * @param string $actionName
     *
     * @return string
     */
    abstract protected function getViewName($actionName);

    /**
     * @return bool
     */
    protected function isPaginated()
    {
        return true;
    }

    /**
     * @return bool
     */
    protected function isIndexAuthorized()
    {
        return true;
    }

    /**
     * @return QueryBuilder
     */
    protected function getIndexCrudQueryBuilder()
    {
        $em = $this->getDoctrineManager();
        return $em->createQueryBuilder()
            ->select('e')
            ->from($this->getEntityName(), 'e');
    }

    public function indexAction(Request $request)
    {
        if (!$this->isIndexAuthorized()) {
            throw $this->createAccessDeniedException();
        }
        $page = $request->query->get("page")
            ? $request->query->get("page")
            : 1;
        $limit = $request->query->get("limit")
            ? $request->query->get("limit")
            : 10;
        $entityQuery = $this->getIndexCrudQueryBuilder();
        $entities = $this->isPaginated()
            ? $this->get("knp_paginator")->paginate($entityQuery->getQuery(), $page, $limit)
            : $entityQuery->getQuery()->getResult();
        return $this->render($this->getIndexViewName(), [
            'entities' => $entities
        ]);
    }

}
