<?php
namespace Mero\BaseBundle\Controller;

<<<<<<< HEAD
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

abstract class StdController extends Controller
{

=======
use Rhumsaa\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @package Mero\BaseBundle\Controller
 * @author Rafael Mello <merorafael@gmail.com>
 * @link https://github.com/merorafael/MeroBaseBundle Repositório do projeto
 * @link http://merorafael.wordpress.com Blog pessoal
 * @Copyright Copyright (c) 2014~2015 - Rafael Mello
 * @license https://github.com/merorafael/MeroBaseBundle/blob/master/LICENSE MIT
 */
class StdController extends Controller
{

    /**
     * Retorna nome referente ao bundle.
     *
     * @return string Nome do bundle
     */
    protected function getBundleName()
    {
        $namespace_explode = explode("\\", get_class($this));
        $bundle_name = '';
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
     * Gera string UUID na versão 1(baseada no tempo).
     *
     * @return string UUID
     */
    protected function createUuid1()
    {
        return Uuid::uuid1()->toString();
    }

    /**
     * Gera string UUID na versão 3(baseada no nome e criptografada em MD5).
     * Caso não informe namespace o método o criará.
     *
     * @param string $nome Nome
     * @param string $ns Namespace
     * @return string UUID
     */
    protected function createUuid3($nome, $ns = null)
    {
        if ($ns === null) {
            $ns = Uuid::NAMESPACE_DNS;
        }
        return Uuid::uuid3($ns, $nome)->toString();
    }

    /**
     * Gera string UUID na versão 4(aleatório).
     *
     * @return string UUID
     */
    protected function createUuid4()
    {
        return Uuid::uuid4()->toString();
    }

    /**
     * Gera string UUID na versão 5(baseada no nome e criptografada em SHA1).
     * Caso não informe namespace o método o criará.
     *
     * @param string $nome Nome
     * @param string $ns Namespace
     * @return string UUID
     */
    protected function createUuid5($nome, $ns = null)
    {
        if ($ns === null) {
            $ns = Uuid::NAMESPACE_DNS;
        }
        return Uuid::uuid5($ns, $nome)->toString();
    }

>>>>>>> 051fc2dc0d2b748141c27d95c7b42e15f91df8f4
}
