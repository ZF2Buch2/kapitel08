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
