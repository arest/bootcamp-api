<?php

namespace AdminBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class AdminExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('simple_admin.routes', $config['routes']);
        $container->setParameter('simple_admin.pagination.per_page', $config['pagination']['per_page']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('controller.yml');
        $loader->load('event.yml');
        $loader->load('form.yml');
        $loader->load('handler.yml');
        $loader->load('repository.yml');
        $loader->load('routing.yml');
        $loader->load('twig.yml');
        $loader->load('voter.yml');
    }
}
