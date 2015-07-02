<?php
namespace Mero\Bundle\BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class StdCrudController
 *
 * @package Mero\Bundle\BaseBundle\Controller
 * @author Rafael Mello <merorafael@gmail.com>
 * @Copyright Copyright (c) 2014~2015 - Rafael Mello
 * @license https://github.com/merorafael/MeroBaseBundle/blob/master/LICENSE MIT license
 */
abstract class StdCrudController extends Controller
{

    /**
     * Verificador de CRUD na indexAction.
     *
     * @return bool
     */
    protected function isIndexCrud()
    {
        return $this->get("mero_base.index_crud");
    }

    /**
     * Verificador de conteúdo paginado no indexAction.
     *
     * @return bool
     */
    protected function isDataPagination()
    {
        return $this->get("mero_base.data_pagination");
    }

    /**
     * Retorna nome da rota referente a action informada.
     *
     * @param string $action Nome da action(indexAction, addAction, editAction ou removeAction)
     *
     * @return string
     */
    abstract protected function getRoute($action);

    abstract protected function getFilterForm();

    public function indexAction()
    {
        $this_route = $this->getRoute(__METHOD__);
    }

}
