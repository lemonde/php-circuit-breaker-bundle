<?php

namespace CircuitBreakerBundle;

use CircuitBreakerBundle\DependencyInjection\CircuitBreakerExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @codeCoverageIgnore
 */
class CircuitBreakerBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
    }

    public function getContainerExtension()
    {
        return new CircuitBreakerExtension();
    }
}
