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
namespace Pizza\Controller\Plugin;

use Zend\Debug\Debug;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Dump plugin
 * 
 * @package    Pizza
 */
class Dump extends AbstractPlugin
{
    /**
     * Invoke zur direkten Ausgabe
     *
     * @param  mixed $data
     * @return string
     */
    public function __invoke($data = null, $echo = true)
    {
        return Debug::dump($data, null, $echo);
    }
}
