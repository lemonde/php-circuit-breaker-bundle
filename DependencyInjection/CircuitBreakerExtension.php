<?php declare(strict_types = 1);

namespace CircuitBreakerBundle\DependencyInjection;

use CircuitBreakerBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @codeCoverageIgnore
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class CircuitBreakerExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter('circuit_breaker.timeout', $config['timeout']);
        $container->setParameter('circuit_breaker.threshold', $config['threshold']);
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources'));
        $loader->load('services.yml');
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'circuit_breaker';
    }
}
