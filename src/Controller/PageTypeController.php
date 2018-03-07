<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\CPanel\Doctrine\Controller;

use MSBios\CPanel\Controller\PageTypeController as DefaultPageTypeController;
use MSBios\CPanel\Doctrine\Mvc\Controller\AbstractActionController;
use MSBios\CPanel\Doctrine\Mvc\Controller\AbstractLazyActionController;
use MSBios\Resource\Doctrine\Entity\PageType;

/**
 * Class PageTypeController
 * @package MSBios\CPanel\Doctrine\Controller
 */
class PageTypeController extends AbstractActionController // AbstractLazyActionController
{
    /**
     * PageTypeController constructor.
     */
    public function __construct()
    {
        $this->setObjectPrototype(new PageType);
    }

    /**
     * @return string
     */
    public function getResourceId()
    {
        return DefaultPageTypeController::class;
    }
}
