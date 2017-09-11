<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\CPanel\Doctrine\Controller;

use MSBios\CPanel\Controller\LayoutController as DefaultLayoutController;
use MSBios\CPanel\Doctrine\Mvc\Controller\AbstractLazyActionController;
use MSBios\Resource\Doctrine\Entity\Layout;

/**
 * Class LayoutController
 * @package MSBios\CPanel\Doctrine\Controller
 */
class LayoutController extends AbstractLazyActionController
{
    /**
     * LayoutController constructor.
     */
    public function __construct()
    {
        $this->setObjectPrototype(new Layout);
    }

    /**
     * @return string
     */
    public function getResourceId()
    {
        return DefaultLayoutController::class;
    }
}
