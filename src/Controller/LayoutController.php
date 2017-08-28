<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\CPanel\Doctrine\Controller;

use MSBios\CPanel\Doctrine\Mvc\Controller\AbstractLazyActionController;

/**
 * Class LayoutController
 * @package MSBios\CPanel\Doctrine\Controller
 */
class LayoutController extends AbstractLazyActionController
{
    /**
     * @return string
     */
    public function getResourceId()
    {
        return \MSBios\CPanel\Controller\LayoutController::class;
    }
}