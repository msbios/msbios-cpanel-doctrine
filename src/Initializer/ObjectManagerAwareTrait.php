<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\CPanel\Doctrine\Initializer;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Trait ObjectManagerAwareTrait
 * @package MSBios\CPanel\Doctrine\Initializer
 */
trait ObjectManagerAwareTrait
{
    /** @var EntityManagerInterface */
    protected $objectManager;

    /**
     * @return EntityManagerInterface
     */
    public function getObjectManager(): EntityManagerInterface
    {
        return $this->objectManager;
    }

    /**
     * @param EntityManagerInterface $objectManager
     * @return EntityManagerInterface
     */
    public function setObjectManager(EntityManagerInterface $objectManager): EntityManagerInterface
    {
        $this->objectManager = $objectManager;
        return $this;
    }

}
