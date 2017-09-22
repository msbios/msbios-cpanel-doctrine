<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\CPanel\Doctrine\Initializer;

use Doctrine\Common\Persistence\ObjectManager;

/**
 * Trait ObjectManagerAwareTrait
 * @package MSBios\CPanel\Doctrine\Initializer
 */
trait ObjectManagerAwareTrait
{
    /** @var ObjectManager */
    protected $objectManager;

    /**
     * @return ObjectManager
     */
    public function getObjectManager()
    {
        return $this->objectManager;
    }

    /**
     * @param ObjectManager $objectManager
     * @return $this
     */
    public function setObjectManager(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
        return $this;
    }

}
