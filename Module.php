<?php

/**
 * Module of PHP Error
 *
 * @author Witold Wasiczko <witold@wasiczko.pl>
 */

namespace ZfSnapPhpError;

use Zend\EventManager\EventInterface;
use Zend\Http\PhpEnvironment\Request;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceManager;

class Module implements ConfigProviderInterface, BootstrapListenerInterface
{

    /**
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * @param \Zend\EventManager\EventInterface $e
     */
    public function onBootstrap(EventInterface $e)
    {
        if (!$e->getRequest() instanceof Request) {
            return;
        }

        $application  = $e->getApplication();
        $config       = $application->getConfig();
        $moduleConfig = $config['php-error'];

        if (!$moduleConfig['enabled']) {
            return;
        }

        $serviceManager = $application->getServiceManager();

        $phperror = \php_error\reportErrors($moduleConfig['options']);
        $phperror->addCustomData('service_manager', $config['service_manager']);
        $phperror->addCustomData('modules', $this->getModules($serviceManager));

        $serviceManager->setService('phperror', $phperror);

        $eventManager = $application->getEventManager();
        $eventManager->attach(MvcEvent::EVENT_ROUTE, array($this, 'onRoute'));
    }

    /**
     * @param ServiceManager $serviceManager
     * @return array
     */
    private function getModules(ServiceManager $serviceManager)
    {
        $moduleManager = $serviceManager->get('ModuleManager');

        return array_keys($moduleManager->getLoadedModules());
    }

    /**
     * @param MvcEvent $e
     */
    public function onRoute(MvcEvent $e)
    {
        $route      = $e->getRouteMatch();
        $machedName = $route->getMatchedRouteName();
        $params     = $route->getParams();

        $phperror = $e->getApplication()->getServiceManager()->get('phperror');
        $key      = sprintf('route %s', $machedName);
        $phperror->addCustomData($key, $params);
    }
}