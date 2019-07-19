<?php

namespace Trilix\EventsApiBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RegisterEventTypesPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $eventTypeConfigurationList = $container->getDefinition('pim_events_api.event_type_configuration_list');

        $eventTypeConfigurations = $container->findTaggedServiceIds('pim_events_api.event_type_configuration');
        foreach ($eventTypeConfigurations as $serviceId => $tags) {
            $eventTypeConfigurationList->addMethodCall('addEventTypeConfiguration', [new Reference($serviceId)]);
        }
    }
}
