<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\CPanel\Doctrine\Controller;

use MSBios\CPanel\Controller\SettingController as DefaultSettingController;
use MSBios\CPanel\Doctrine\Mvc\Controller\AbstractActionController;
use MSBios\Resource\Record\Setting;

/**
 * Class SettingController
 * @package MSBios\CPanel\Controller
 */
class SettingController extends AbstractActionController
{
    /**
     * @return string
     */
    public function getResourceId()
    {
        return DefaultSettingController::class;
    }

    /**
     * @return mixed|Setting
     */
    protected static function factory()
    {
        return new Setting;
    }
}
