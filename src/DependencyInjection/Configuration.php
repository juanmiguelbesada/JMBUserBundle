<?php

namespace JMB\UserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('jmb_user');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('from_email')->defaultValue('noreply@example.com')->cannotBeEmpty()->end()
                ->booleanNode('user_class')->defaultNull()->info('The app user class')->end()
                ->arrayNode('forgot_password')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('template')->defaultValue('@JMBUser/forgot_password/email.html.twig')->cannotBeEmpty()->end()
                            ->scalarNode('confirmation_route')->defaultValue('jmb_user_forgot_password_confirmation')->cannotBeEmpty()->end()
                        ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
