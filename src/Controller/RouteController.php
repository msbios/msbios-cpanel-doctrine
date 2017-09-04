<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\CPanel\Doctrine\Controller;

use MSBios\CPanel\Controller\RouteController as DefaultRouteController;
use MSBios\CPanel\Doctrine\Mvc\Controller\AbstractLazyActionController;
use MSBios\Resource\Doctrine\Entity\Route;

/**
 * Class RouteController
 * @package MSBios\CPanel\Doctrine\Controller
 */
class RouteController extends AbstractLazyActionController
{
    /**
     * RouteController constructor.
     */
    public function __construct()
    {
        $this->setObjectPrototype(new Route);
    }

    /**
     * @return string
     */
    public function getResourceId()
    {
        return DefaultRouteController::class;
    }
}
