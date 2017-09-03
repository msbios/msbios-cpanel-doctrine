<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 *
 */

namespace MSBios\CPanel\Doctrine\Controller;

use MSBios\CPanel\Controller\IndexController as DefaultIndexController;
use MSBios\CPanel\Mvc\Controller\AbstractActionController;

/**
 * Class IndexController
 * @package MSBios\CPanel\Doctrine\Controller
 */
class IndexController extends AbstractActionController
{
    /**
     * @return string
     */
    public function getResourceId()
    {
        return DefaultIndexController::class;
    }
}