<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\CPanel\Doctrine\Mvc\Controller;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Interface EntityManagerAwareInterface
 * @package MSBios\CPanel\Doctrine\Mvc\Controller
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
