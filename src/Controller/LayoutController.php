<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\CPanel\Doctrine\Controller;

use MSBios\CPanel\Doctrine\Mvc\Controller\AbstractActionController;

/**
 * Class LayoutController
 * @package MSBios\CPanel\Doctrine\Controller
 */
class LayoutController extends AbstractActionController
{
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