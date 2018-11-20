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
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\Mvc\ApplicationInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class Module
 * @package MSBios\CPanel\Doctrine
 */
class Module implements ModuleInterface, ModuleAwareInterface, AutoloaderAwareInterface
{
    /** @const VERSION */
    const VERSION = '1.0.27';

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * Return an array for passing to Zend\Loader\AutoloaderFactory.
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
     * Listen to the bootstrap event
     *
     * @param EventInterface $e
     * @return array
     */
    public function onBootstrap(EventInterface $e)
    {
//        /** @var ApplicationInterface $target */
//        $target = $e->getTarget();
//
//        var_dump($target); die();

//        /** @var ServiceLocatorInterface $serviceManager */
//        $serviceManager = $target->getServiceManager();

        // /** @var EventManagerInterface|EventManager $eventManager */
        // $eventManager = $target->getEventManager();
        //
        // (new LazyListenerAggregate(
        //     $serviceManager->get(self::class)['listeners'],
        //     $serviceManager
        // ))->attach($eventManager);
        //
        // $eventManager->attach(MvcEvent::EVENT_RENDER, [$this, 'onRender'], 1);
        // $eventManager->attach(MvcEvent::EVENT_RENDER_ERROR, [$this, 'onRender'], 1);
    }
}
