<?php
/**
 * ZF2 Buch Kapitel 8
 * 
 * Das Buch "Zend Framework 2 - Das Praxisbuch"
 * von Ralf Eggert ist im Galileo-Computing Verlag erschienen. 
 * ISBN 978-3-8362-2610-3
 * 
 * @package    Application
 * @author     Ralf Eggert <r.eggert@travello.de>
 * @copyright  Alle Listings sind urheberrechtlich geschützt!
 * @link       http://www.zendframeworkbuch.de/ und http://www.galileocomputing.de/3460
 */

/**
 * namespace definition and usage
 */
namespace Application\Listener;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Mvc\MvcEvent;

/**
 * RunTime listener
 * 
 * Listens on application level
 * 
 * @package    Application
 */
class RunTimeListener implements ListenerAggregateInterface
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
            MvcEvent::EVENT_FINISH, array($this, 'displayRuntime')
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
    public function displayRuntime(MvcEvent $e)
    {
        $response = $e->getApplication()->getResponse();
        
        $body = $response->getBody();
        
        $startTime = ZF2_TIME_START;
        $endTime   = microtime(true);
        $diffTime  = $endTime - $startTime;
        
        $runtime = '<div style="border: 1px solid #eee; color: #888; ';
        $runtime.= 'background-color: #f8f8f8; text-align: center; ';
        $runtime.= 'margin: 10px; padding:10px;">';
        $runtime.= 'Laufzeit: ' . round($diffTime, 5) . ' Sekunden';
        $runtime.= '</div>';
        
        $body = str_replace('</body>', $runtime . '</body>', $body);
        
        $response->setContent($body);
    }
}
