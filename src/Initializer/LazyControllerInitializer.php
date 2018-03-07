<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\CPanel\Doctrine\Initializer;

use Doctrine\ORM\EntityManager;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Initializer\InitializerInterface;

/**
 * Class LazyControllerInitializer
 * @package MSBios\CPanel\Doctrine\Initializer
 * @deprecated remove this class
 */
class LazyControllerInitializer implements InitializerInterface
{
    /**
     * @param ContainerInterface $container
     * @param object $instance
     */
    public function __invoke(ContainerInterface $container, $instance)
    {
        // TODO: Doctrine has ObjectManagerAwareInterface
        if ($instance instanceof EntityManagerAwareInterface) {
            $instance->setEntityManager(
                $container->get(EntityManager::class)
            );
        }

        if ($instance instanceof ObjectManagerAwareInterface) {
            $instance->setObjectManager(
                $container->get(EntityManager::class)
            );
        }

        if ($instance instanceof DoctrineHydratorAwareInterface) {
            $instance->setHydrator(
                $container->get('HydratorManager')->get(DoctrineObject::class)
            );
        }
    }

    /**
     * @param $an_array
     * @return LazyControllerInitializer
     */
    public static function __set_state($an_array)
    {
        return new self();
    }
}
