<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\CPanel\Doctrine\Initializer;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

/**
 * Interface DoctrineHydratorAwareInterface
 * @package MSBios\CPanel\Doctrine\Initializer
 * @deprecated todo: remove in future
 */
interface DoctrineHydratorAwareInterface
{
    /**
     * @return DoctrineObject
     */
    public function getHydrator();

    /**
     * @param DoctrineObject $hydrator
     * @return $this
     */
    public function setHydrator(DoctrineObject $hydrator);
}
