<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\CPanel\Doctrine\Controller;

use MSBios\CPanel\Doctrine\Mvc\Controller\AbstractActionController;
use MSBios\Resource\Doctrine\Entity\Layout;

/**
 * Class LayoutController
 * @package MSBios\CPanel\Doctrine\Controller
 */
class LayoutController extends AbstractActionController
{
    /**
     * @return mixed
     */
    protected static function factory()
    {
        return new Layout;
    }

    /**
     * Returns the string identifier of the Resource
     *
     * @return string
     */
    public function getResourceId()
    {
        return \MSBios\CPanel\Controller\LayoutController::class;
    }
}
