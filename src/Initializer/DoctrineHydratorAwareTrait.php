<?php
/**
 * @access protected
 * @author Judzhin Miles <judzhin[at]gns-it.com>
 */
namespace MSBios\CPanel\Doctrine\Initializer;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

/**
 * Trait DoctrineHydratorAwareTrait
 * @package MSBios\CPanel\Doctrine\Initializer
 */
trait DoctrineHydratorAwareTrait
{
    /** @var DoctrineObject */
    protected $hydrator;

    /**
     * @return DoctrineObject
     */
    public function getHydrator()
    {
        return $this->hydrator;
    }

    /**
     * @param DoctrineObject $hydrator
     * @return $this
     */
    public function setHydrator(DoctrineObject $hydrator)
    {
        $this->hydrator = $hydrator;
        return $this;
    }
}