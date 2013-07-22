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
namespace Pizza\Controller;

use Pizza\Service\PizzaService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Index controller
 * 
 * Handles the pages for the Pizza module
 * 
 * @package    Pizza
 */
class IndexController extends AbstractActionController
{
    /**
     * Pizza service
     *
     * @var PizzaService
     */
    protected $service = null;
    
    /**
     * Get dependencies
     * 
     * @param PizzaService $service
     */
    public function __construct(PizzaService $service)
    {
        $this->setPizzaService($service);
    }
    
    /**
     * Set pizza service
     * 
     * @param PizzaService $service
     */
    public function setPizzaService(PizzaService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Get pizza service
     * 
     * @return PizzaService
     */
    public function getPizzaService()
    {
        return $this->service;
    }
    
    /**
     * Handle pizza intro page
     */
    public function indexAction()
    {
        $pizzaList = $this->getPizzaService()->fetchList();

        return new ViewModel(array(
            'pizzaList'   => $pizzaList,
        ));
    }
    
    /**
     * Handle pizza show page
     */
    public function showAction()
    {
        $id = $this->getEvent()->getRouteMatch()->getParam('id');
        
        $pizzaRow = $this->getPizzaService()->fetchSingleById($id);
        
        return new ViewModel(array(
            'pizzaRow' => $pizzaRow,
        ));
    }
}
