<?php

namespace Trilix\EventsApiBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Trilix\EventsApiBundle\Transport\Transport;

class TrilixEventsApiExtension extends Extension
{
    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('events.yml');
        $loader->load('jobs.yml');

        $config = $this->processConfiguration(new Configuration(), $configs);

        $transportFactory = $config['transport']['factory'];
        $transportOptions = $config['transport']['options'] ?? [];

        $transportDefinition = new Definition(Transport::class);
        $transportDefinition->setFactory([$container->findDefinition($transportFactory), 'create']);
        $transportDefinition->setArguments([$transportOptions]);

        $container->setDefinition('pim_events_api.transport.default', $transportDefinition);
    }
}
