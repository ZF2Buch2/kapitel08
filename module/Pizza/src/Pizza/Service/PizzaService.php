<?php
/**
 * ZF2 Buch Kapitel 8
 * 
 * Das Buch "Zend Framework 2 - Von den Grundlagen bis zur fertigen Anwendung"
 * von Ralf Eggert ist im Addison-Wesley Verlag erschienen. 
 * ISBN 978-3-8273-2994-3
 * 
 * @package    Pizza
 * @author     Ralf Eggert <r.eggert@travello.de>
 * @copyright  Alle Listings sind urheberrechtlich geschützt!
 * @link       http://www.zendframeworkbuch.de/ und http://www.awl.de/2994
 */

/**
 * namespace definition and usage
 */
namespace Pizza\Service;

use Pizza\Exception\PizzaException;

/**
 * Pizza model service
 * 
 * Handles the pizza service model
 * 
 * @package    Pizza
 */
class PizzaService
{
    /**
     * Pizza list
     * 
     * @var array
     */
    protected $pizzaList = array(
        1 => 'Salami', 
        2 => 'Hawaii', 
        3 => 'Melone', 
        4 => 'Schinken', 
        5 => 'Thunfisch',
        6 => 'Speziale', 
        7 => 'Calzone', 
        8 => 'Vierjahreszeiten', 
        9 => 'Spinat',
    );
    
    /**
     * Fetch pizza list
     *
     * @return array pizza list
     */
    public function fetchList()
    {
        return $this->pizzaList;
    }
    
    /**
     * Fetch pizza single by id
     * 
     * @param $id integer id of pizza
     * @return string pizza name
     */
    public function fetchSingleById($id = null)
    {
        if (is_null($id)) {
            throw new PizzaException('Pizza ID invalid');
        } elseif (!isset($this->pizzaList[$id])) {
            throw new PizzaException('Pizza not found');
        }

        return array($id => $this->pizzaList[$id]);
    }
    
    /**
     * Add pizza
     *
     * @param $data new pizza data
     * @return integer primary key
     */
    public function add($data = null)
    {
        $id = count($this->pizzaList) + 1;
        
        $this->pizzaList[$id] = $data['name'];
        
        return $id;
    }
    
    /**
     * Update pizza
     *
     * @param $id integer id of pizza
     * @param $data pizza data
     * @return integer primary key
     */
    public function update($id, $data = null)
    {
        $this->pizzaList[$id] = $data['name'];
        
        return $id;
    }
    
    /**
     * Delete pizza
     *
     * @param $id integer id of pizza
     * @return boolean
     */
    public function delete($id)
    {
        unset($this->pizzaList[$id]);
        
        return true;
    }
}
