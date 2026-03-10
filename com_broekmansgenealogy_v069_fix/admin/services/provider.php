<?php
defined('_JEXEC') or die;

use Joomla\CMS\Dispatcher\ComponentDispatcherFactoryInterface;
use Joomla\CMS\Extension\ComponentInterface;
use Joomla\CMS\Extension\Service\Provider\ComponentDispatcherFactory;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\CMS\Extension\Service\Provider\RouterFactory;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

return new class() implements ServiceProviderInterface {
    public function register(Container $container): void
    {
        $container->registerServiceProvider(new MVCFactory('\\Broekmans\\Component\\Broekmansgenealogy'));
        $container->registerServiceProvider(new ComponentDispatcherFactory('\\Broekmans\\Component\\Broekmansgenealogy'));
        $container->registerServiceProvider(new RouterFactory('\\Broekmans\\Component\\Broekmansgenealogy'));

        $container->set(
            ComponentInterface::class,
            function (Container $container) {
                return new \Broekmans\Component\Broekmansgenealogy\Administrator\Extension\BroekmansgenealogyComponent(
                    $container->get(ComponentDispatcherFactoryInterface::class)
                );
            }
        );
    }
};
