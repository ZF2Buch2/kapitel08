<?php
/**
 * ZF2 Buch Kapitel 8
 * 
 * Das Buch "Zend Framework 2 - Von den Grundlagen bis zur fertigen Anwendung"
 * von Ralf Eggert ist im Addison-Wesley Verlag erschienen. 
 * ISBN 978-3-8273-2994-3
 * 
 * @package    Application
 * @author     Ralf Eggert <r.eggert@travello.de>
 * @copyright  Alle Listings sind urheberrechtlich geschützt!
 * @link       http://www.zendframeworkbuch.de/ und http://www.awl.de/2994
 */

/**
 * namespace definition and usage
 */
namespace Application\Listener;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Log\Logger;
use Zend\Mvc\MvcEvent;

/**
 * Logging listener
 * 
 * Listens on application level
 * 
 * @package    Application
 */
class LoggingListener implements ListenerAggregateInterface
{
    /**
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = array();

    /**
     * Attach to an event manager
     *
     * @param  EventManagerInterface $events
     * @param  integer $priority
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(
            MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'logErrors'), -100
        );
    }

    /**
     * Detach all our listeners from the event manager
     *
     * @param  EventManagerInterface $events
     * @return void
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    /**
     * Log the errors
     *
     * @param  MvcEvent $e
     * @return null
     */
    public function logErrors(MvcEvent $e)
    {
        $sm = $e->getApplication()->getServiceManager();
        $logger = $sm->get('Application\Logging\Service');
        
        $exception = $e->getResult()->exception;
        
        $message = $exception->getCode() . ": " . $exception->getMessage() . "\n";
        $message.= str_pad('-', 120, '-') . "\n";
        $message.= $exception->getFile() . " (" . $exception->getLine() . ")\n";
        $message.= str_pad('-', 120, '-') . "\n";
        $message.= $exception->getTraceAsString() . "\n";
        $message.= str_pad('-', 120, '-') . "\n";
        
        $logger->log(Logger::ERR, $message);
    }
}
