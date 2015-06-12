<?php
namespace Mero\BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @package Mero\BaseBundle\Controller
 */
abstract class StdController extends Controller
{

    /**
     * Return current bundle name.
     *
     * <b>Attention! This method returns the bundle name as the conventions of framewok.</b>
     * Example: "Mero/Bundle/BaseBundle" or "Mero/BaseBundle" returns "MeroBaseBundle".
     * 
     * @return string
     */
    protected function getBundleName()
    {
        $current_class = explode("\\", get_class($this));
        if (in_array("Bundle", $current_class, true)) {
            unset($current_class[array_search("Bundle", $current_class, true)]);
        }
        $bundle_name = "";
        foreach ($current_class as &$directory) {
            $bundle_name .= $directory;
            if (strpos($directory, "Bundle")) {
                break;
            }
        }
        return $bundle_name;
    }

}
