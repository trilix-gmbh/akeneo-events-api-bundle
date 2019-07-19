<?php

namespace Trilix\EventsApiBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class TrilixEventsApiExtension extends Extension
{
    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);
        $applications = $config['applications'] ?? [];

        foreach ($applications as $code => $cfg) {
            $container->setParameter(sprintf('%s.%s.uri', $this->getAlias(), $code), $cfg['uri']);
        }

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('events.yml');
        $loader->load('jobs.yml');
    }
}
