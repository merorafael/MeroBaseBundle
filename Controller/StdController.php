<?php
namespace Mero\BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

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
     * Utiliza o objeto JsonResponse do Symfony para retornar uma resposta de
     * requisição HTTP no formato de JSON. Este método utiliza o Content-Type
     * application/json.
     *
     * @param mixed $response Arraylist da resposta
     * @param int $http_status Status HTTP
     * @return JsonResponse
     */
    protected function getJsonResponse($response = null, $http_status = 200)
    {
        if ($response === null) {
            $response = new \ArrayObject();
        }
        return new JsonResponse(
            $response,
            $http_status,
            array(
                'Content-Type' => 'application/json'
            )
        );
    }

}
