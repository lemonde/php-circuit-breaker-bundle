<?php

namespace CircuitBreakerBundle;

use CircuitBreakerBundle\DependencyInjection\CircuitBreakerExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CircuitBreakerBundle extends Bundle
{
    /**
     * @codeCoverageIgnore
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
    }

    public function getContainerExtension()
    {
        return new CircuitBreakerExtension();
    }
}
