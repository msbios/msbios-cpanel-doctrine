<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\CPanel\Doctrine\Controller;

use MSBios\CPanel\Controller\ModuleController as DefaultModuleController;
use MSBios\CPanel\Doctrine\Mvc\Controller\AbstractActionController;
use MSBios\Resource\Doctrine\Entity\Module;

/**
 * Class ModuleController
 * @package MSBios\CPanel\Doctrine\Controller
 */
class ModuleController extends AbstractActionController
{
    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getResourceId()
    {
        return DefaultModuleController::class;
    }

    /**
     * @inheritdoc
     *
     * @return mixed|Module
     */
    protected static function factory()
    {
        return new Module;
    }
}
