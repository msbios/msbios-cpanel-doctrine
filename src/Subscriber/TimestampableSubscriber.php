<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\CPanel\Doctrine\Subscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use MSBios\Resource\Doctrine\EntityInterface;
use MSBios\Resource\Doctrine\TimestampableAwareInterface;

/**
 * Class TimestampableSubscriber
 * @package MSBios\CPanel\Doctrine\Subscriber
 */
class TimestampableSubscriber implements EventSubscriber
{
    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return string[]
     */
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        /** @var EntityInterface $entity */
        $entity = $args->getObject();
        if ($entity instanceof TimestampableAwareInterface) {
            $entity
                ->setCreatedAt(new \DateTime)
                ->setModifiedAt(new \DateTime);
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        /** @var EntityInterface $entity */
        $entity = $args->getObject();
        if ($entity instanceof TimestampableAwareInterface) {
            $entity
                ->setModifiedAt(new \DateTime);
        }
    }
}
