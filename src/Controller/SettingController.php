<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\CPanel\Doctrine\Controller;

use MSBios\CPanel\Controller\SettingController as DefaultSettingController;
use MSBios\CPanel\Doctrine\Mvc\Controller\AbstractLazyActionController;
use MSBios\Resource\Doctrine\Entity\Session;

/**
 * Class SettingController
 * @package MSBios\CPanel\Controller
 */
class SettingController extends AbstractLazyActionController
{
    /**
     * SettingController constructor.
     */
    public function __construct()
    {
        $this->setObjectPrototype(new Session);
    }

    /**
     * @return string
     */
    public function getResourceId()
    {
        return DefaultSettingController::class;
    }
}
