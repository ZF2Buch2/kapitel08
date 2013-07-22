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
namespace Pizza\Listener;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Log\Logger;
use Zend\Mvc\MvcEvent;

/**
 * Logging listener
 * 
 * Listens on application level
 * 
 * @package    Pizza
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
        $logger = $sm->get('Pizza\Logging\Service');
        
        $exception = $e->getResult()->exception;
        
        if (!$exception) {
            return false;
        }
        
        $message = $exception->getCode() . ": ";
        $message.= $exception->getMessage() . "\n";
        $message.= str_pad('-', 120, '-') . "\n";
        $message.= $exception->getFile() . " (";
        $message.= $exception->getLine() . ")\n";
        $message.= str_pad('-', 120, '-') . "\n";
        $message.= $exception->getTraceAsString() . "\n";
        $message.= str_pad('-', 120, '-') . "\n";
        
        $logger->log(Logger::ERR, $message);
    }
}
