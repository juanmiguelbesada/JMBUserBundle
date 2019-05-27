<?php

namespace JMB\UserBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class JMBUserExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('jmb_user.from_email', $config['from_email']);

        if (null === $config['user_class'] && class_exists('App\Entity\User')) {
            $container->setParameter('jmb_user.user_class', 'App\Entity\User');
        }

        $container->setParameter('jmb_user.forgot_password.template', $config['forgot_password']['template']);
        $container->setParameter('jmb_user.reset_password.confirmation_route', $config['forgot_password']['confirmation_route']);
    }
}
