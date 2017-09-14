<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\CPanel\Doctrine\Initializer;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Interface EntityManagerAwareInterface
 * @package MSBios\CPanel\Doctrine\Initializer
 * @deprecated DoctrineModule has ObjectManagerAwareInterface
 */
interface EntityManagerAwareInterface
{
    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager();

    /**
     * @param EntityManagerInterface $entityManager
     * @return $this
     */
    public function setEntityManager(EntityManagerInterface $entityManager);
}
