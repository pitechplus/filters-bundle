<?php

namespace Pitech\FiltersBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class PitechFiltersExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $configDir = __DIR__.'/../Resources/config';
        $loader = new Loader\YamlFileLoader($container, new FileLocator($configDir));
        $paths = scandir(sprintf('%s/services', $configDir));

        foreach ($paths as $path) {
            if (is_file(sprintf('%s/services/%s', $configDir, $path))) {
                $loader->load(sprintf('services/%s', $path));
            }
        }
    }
}
