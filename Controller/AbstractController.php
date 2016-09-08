<?php

namespace Mero\Bundle\BaseBundle\Controller;

use Mero\Bundle\BaseBundle\Exception\UnsupportedFormatException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AbstractController extends Controller
{

    /**
     * Constant with webservice response type JSON
     */
    const WS_RESPONSE_JSON = 'json';

    /**
     * Constant with webservice response type XML
     */
    const WS_RESPONSE_XML = 'xml';

    /**
     * Gets the current request object.
     *
     * @return Request
     */
    protected function getCurrentRequest()
    {
        return $this->container->get('request_stack')->getCurrentRequest();
    }

    /**
     * Gets the action name.
     *
     * @param Request $request HTTP request object
     *
     * @return string Action name
     */
    protected function getActionName(Request &$request)
    {
        $action = explode('::', $request->attributes->get('_controller'));

        return $action[1];
    }

    /**
     * Gets the bundle name.
     *
     * <b>Attention! This method returns the bundle name as the conventions of framewok.</b>
     * Example: "Mero/Bundle/BaseBundle" or "Mero/BaseBundle" returns "MeroBaseBundle".
     *
     * @return string
     */
    protected function getBundleName()
    {
        $matches = [];
        $className = str_replace('\Bundle\\', '', get_class($this));
        preg_match('/(.*)\\\Controller/', $className, $matches);

        return count($matches) != 0
            ? $matches[1]
            : null;
    }

    /**
     * Gets the route name.
     *
     * @param Request $request HTTP request object
     *
     * @return string Route name
     */
    protected function getRouteName(Request &$request)
    {
        return $request->attributes->get('_route');
    }

    /**
     * Return webservice(API Rest JSON or SOAP XML) response.
     *
     * @param mixed  $data    The response data
     * @param int    $status  The response status code
     * @param array  $headers An array of response headers
     * @param string $format  Response format(json or xml)
     *
     * @throws UnsupportedFormatException When format is not json or xml
     *
     * @return Response
     */
    protected function wsResponse($data, $status = 200, array $headers = [], $format = self::WS_RESPONSE_JSON)
    {
        if (!in_array($format, ['json', 'xml'])) {
            throw new UnsupportedFormatException();
        }

        return new Response(
            $this->container->get('serializer')->serialize($data, $format),
            $status,
            $headers
        );
    }
}
