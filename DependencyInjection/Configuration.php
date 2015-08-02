<?php
namespace Mero\Bundle\BaseBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('mero_base');

        $rootNode
            ->children()
                ->booleanNode("data_pagination")->defaultTrue()->cannotBeEmpty()->end()
                ->booleanNode("page_range")->defaultValue(10)->cannotBeEmpty()->end()
                ->booleanNode("index_crud")->defaultFalse()->cannotBeEmpty()->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
