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
     * @inheritdoc
     *
     * @return array|string[]
     */
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    /**
     * @inheritdoc
     *
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
     * @inheritdoc
     *
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
