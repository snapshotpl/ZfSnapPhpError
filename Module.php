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
use \Exception;

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
     * @param EventInterface $e
     */
    public function onBootstrap(EventInterface $e)
    {
        if (!$e->getRequest() instanceof Request) {
            return;
        }

        /* @var $application \Zend\Mvc\Application */
        $application  = $e->getApplication();
        $config       = $application->getConfig();
        $moduleConfig = $config['php-error'];

        if ($moduleConfig['enabled'] === false) {
            return;
        }

        $serviceManager = $application->getServiceManager();

        $phperror = \php_error\reportErrors($moduleConfig['options']);
        $phperror->addCustomData('service_manager', $config['service_manager']);
        $phperror->addCustomData('modules', $this->getModules($serviceManager));

        $serviceManager->setService('phperror', $phperror);

        $eventManager = $application->getEventManager();

        $eventManager->attach(MvcEvent::EVENT_ROUTE, function (MvcEvent $e) use ($phperror) {
            $route      = $e->getRouteMatch();
            $machedName = $route->getMatchedRouteName();
            $params     = $route->getParams();
            $key = sprintf('current route "%s"', $machedName);

            $phperror->addCustomData($key, $params);
        });

        $listener = function (MvcEvent $event) {
            $exception = $event->getParam('exception');

            if ($exception instanceof Exception) {
                throw $exception;
            }
        };
        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, $listener);
        $eventManager->attach(MvcEvent::EVENT_RENDER_ERROR, $listener);
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
}
