<?php

namespace AppBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Finder\Finder;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class AppExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('controller.yml');
        #$loader->load('event.yml');
        $loader->load('form.yml');
        $loader->load('repository.yml');
        $loader->load('voter.yml');

        $finder=new Finder();
        
        foreach($finder->files()->in(__DIR__.'/../Resources/config') as $file)
        {
            $filePath=$file->getRealPath();
            if(preg_match('@\.yml$@',$filePath)===1)
            {
                $yamlMappingFiles[]=$filePath;
            }
            $container->setParameter('validator.mapping.loader.yaml_files_loader.mapping_files', $yamlMappingFiles);
        }

    }
}
