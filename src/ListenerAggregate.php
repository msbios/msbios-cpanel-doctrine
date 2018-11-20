<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\CPanel\Doctrine;

use MSBios\CPanel\Doctrine\Mvc\Controller\AbstractActionController;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;

/**
 * Class ListenerAggregate
 * @package MSBios\CPanel\Doctrine
 */
class ListenerAggregate extends AbstractListenerAggregate
{
    /**
     * @inheritdoc
     *
     * @param EventManagerInterface $events
     * @param int $priority
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $events->attach(AbstractActionController::EVENT_PERSIST_OBJECT, function (EventInterface $e) {
            var_dump($e->getTarget());
        }, 100);
    }
}
