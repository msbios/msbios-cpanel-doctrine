<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\CPanel\Doctrine\Controller;

use MSBios\CPanel\Controller\RouteController as DefaultRouteController;
use MSBios\CPanel\Doctrine\Mvc\Controller\AbstractActionController;
use MSBios\Resource\Doctrine\Entity\Route;

/**
 * Class RouteController
 * @package MSBios\CPanel\Doctrine\Controller
 */
class RouteController extends AbstractActionController
{
    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getResourceId()
    {
        return DefaultRouteController::class;
    }

    /**
     * @inheritdoc
     *
     * @return mixed|Route
     */
    protected static function factory()
    {
        return new Route;
    }
}
