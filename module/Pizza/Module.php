<?php
/**
 * ZF2 Buch Kapitel 8
 * 
 * Das Buch "Zend Framework 2 - Das Praxisbuch"
 * von Ralf Eggert ist im Galileo-Computing Verlag erschienen. 
 * ISBN 978-3-8362-2610-3
 * 
 * @package    Pizza
 * @author     Ralf Eggert <r.eggert@travello.de>
 * @copyright  Alle Listings sind urheberrechtlich geschützt!
 * @link       http://www.zendframeworkbuch.de/ und http://www.galileocomputing.de/3460
 */

/**
 * namespace definition and usage
 */
namespace Pizza;

use Pizza\Listener\LoggingListener;
use Pizza\Listener\RunTimeListener;
use Locale;
use Zend\EventManager\EventInterface;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ControllerProviderInterface;
use Zend\ModuleManager\Feature\ControllerPluginProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\Mvc\MvcEvent;

/**
 * Pizza module class
 * 
 * @package    Pizza
 */
class Module implements 
    BootstrapListenerInterface,
    ConfigProviderInterface,
    AutoloaderProviderInterface,
    ServiceProviderInterface,
    ControllerProviderInterface,
    ControllerPluginProviderInterface
{
    /**
     * Listen to the bootstrap event
     *
     * @param MvcEvent $e
     * @return void
     */
    public function onBootstrap(EventInterface $e)
    {
        // get event manager
        $eventManager = $e->getApplication()->getEventManager();
        
        // add logging listener
        $eventManager->attachAggregate(new LoggingListener());
        
        // add runtime listener
        $eventManager->attachAggregate(new RunTimeListener());
        
        // add language detection
        $eventManager->attach(MvcEvent::EVENT_ROUTE, function ($e) {
            $lang = $e->getRequest()->getQuery('lang', 'de');
            
            Locale::setDefault($lang);
        }, -1000);
        
        // get managers
        $sharedEventManager = $eventManager->getSharedManager();
        $serviceManager     = $e->getApplication()->getServiceManager();
        
        // add JSON view strategy
        $sharedEventManager->attach(__NAMESPACE__, MvcEvent::EVENT_DISPATCH, function($e) use ($serviceManager) {
            $strategy = $serviceManager->get('ViewJsonStrategy');
            $view = $serviceManager->get('ViewManager')->getView();
            $strategy->attach($view->getEventManager());
        });
    }

    /**
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * Return an array for passing to Zend\Loader\AutoloaderFactory.
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getServiceConfig()
    {
        return array(
            'invokables' => array(
                'Pizza\Service' => 'Pizza\Service\PizzaService',
            ),
            'factories' => array(
                'Pizza\Logging\Service' => function ($serviceManager) {
                    $logFile = LUIGI_ROOT . '/data/log/error.log';
                    
                    $writer = new Stream($logFile);
                    $logger = new Logger();
                    $logger->addWriter($writer);
                    
                    return $logger;
                },
            ),
        );
    }
    
    /**
     * Expected to return \Zend\ServiceManager\Config object or array to seed
     * such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getControllerConfig()
    {
        return array(
            'factories' => array(
                'pizza'      => 'Pizza\Controller\IndexControllerFactory',
                'pizza-rest' => 'Pizza\Controller\RestControllerFactory',
            ),
        );
    }
    
    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getControllerPluginConfig()
    {
        return array(
            'invokables' => array(
                'dump' => 'Pizza\Controller\Plugin\Dump',
            ),
        );
    }
}
