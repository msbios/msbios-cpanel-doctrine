<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\CPanel\Doctrine;

use MSBios\AutoloaderAwareInterface;
use MSBios\ModuleAwareInterface;
use MSBios\ModuleInterface;
use Zend\EventManager\EventInterface;
use Zend\Loader\AutoloaderFactory;
use Zend\Loader\StandardAutoloader;

/**
 * Class Module
 * @package MSBios\CPanel\Doctrine
 */
class Module implements ModuleInterface, ModuleAwareInterface, AutoloaderAwareInterface
{
    /** @const VERSION */
    const VERSION = '1.0.37';

    /**
     * @inheritdoc
     *
     * @return array|mixed|\Traversable
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * @inheritdoc
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return [
            AutoloaderFactory::STANDARD_AUTOLOADER => [
                StandardAutoloader::LOAD_NS => [
                    __NAMESPACE__ => __DIR__,
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     *
     * @param EventInterface $e
     */
    public function onBootstrap(EventInterface $e)
    {
        // ...
    }
}
