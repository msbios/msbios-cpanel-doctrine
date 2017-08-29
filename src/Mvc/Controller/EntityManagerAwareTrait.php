<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\CPanel\Doctrine\Mvc\Controller;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Trait EntityManagerAwareTrait
 * @package MSBios\CPanel\Doctrine\Mvc\Controller
 */
trait EntityManagerAwareTrait
{
    /** @var  EntityManagerInterface */
    protected $entityManager;

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @return $this
     */
    public function setEntityManager(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }
}
