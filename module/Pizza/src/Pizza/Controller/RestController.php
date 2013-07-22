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
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

/**
 * Rest controller
 * 
 * Handles the RESTful webservice for the Pizza module
 * 
 * @package    Pizza
 */
class RestController extends AbstractRestfulController
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
     * Return list of resources
     *
     * @return mixed
     */
    public function getList()
    {
        return new JsonModel(array(
            'data' => $this->getPizzaService()->fetchList(),
        ));
    }
    
    /**
     * Return single resource
     *
     * @param  mixed $id
     * @return mixed
     */
    public function get($id)
    {
        return new JsonModel(array(
            'data' => $this->getPizzaService()->fetchSingleById($id),
        ));
    }
    
    /**
     * Create a new resource
     *
     * @param  mixed $data
     * @return mixed
     */
    public function create($data)
    {
        $id = $this->getPizzaService()->add($data);

        return $this->get($id);
    }
    
    /**
     * Update an existing resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return mixed
     */
    public function update($id, $data)
    {
        $this->getPizzaService()->update($id, $data);

        return $this->get($id);
    }
    
    /**
     * Delete an existing resource
     *
     * @param  mixed $id
     * @return mixed
     */
    public function delete($id)
    {
        $this->getPizzaService()->delete($id);
        
        return new JsonModel(array(
            'data' => 'deleted',
        ));
    }
}
