<?php

namespace Trilix\EventsApiBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Trilix\EventsApiBundle\DependencyInjection\Compiler\RegisterEventTypesPass;

class TrilixEventsApiBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new RegisterEventTypesPass());
    }
}
