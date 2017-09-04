<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\CPanel\Doctrine\Controller;

use MSBios\CPanel\Controller\ModuleController as DefaultModuleController;
use MSBios\CPanel\Doctrine\Mvc\Controller\AbstractLazyActionController;
use MSBios\Resource\Doctrine\Entity\Module;

/**
 * Class ModuleController
 * @package MSBios\CPanel\Doctrine\Controller
 */
class ModuleController extends AbstractLazyActionController
{
    /**
     * ModuleController constructor.
     */
    public function __construct()
    {
        $this->setObjectPrototype(new Module);
    }

    /**
     * @return string
     */
    public function getResourceId()
    {
        return DefaultModuleController::class;
    }
}
