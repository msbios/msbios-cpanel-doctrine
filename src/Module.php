<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\CPanel\Doctrine;


use MSBios\ModuleInterface;

/**
 * Class Module
 * @package MSBios\CPanel\Doctrine
 */
class Module implements ModuleInterface
{

    const VERSION = '0.0.1';

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}