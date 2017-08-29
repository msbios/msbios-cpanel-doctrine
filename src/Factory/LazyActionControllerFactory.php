<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\CPanel\Doctrine\Factory;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use MSBios\CPanel\Doctrine\Mvc\Controller\EntityManagerAwareInterface;
use MSBios\CPanel\Factory\LazyActionControllerFactory as DefaultLazyActionControllerFactory;
use MSBios\CPanel\Mvc\Controller\LazyActionControllerInterface;

/**
 * Class LazyActionControllerFactory
 * @package MSBios\CPanel\Doctrine\Factory
 */
class LazyActionControllerFactory extends DefaultLazyActionControllerFactory
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return LazyActionControllerInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var LazyActionControllerInterface $controller */
        $controller = parent::__invoke($container, $requestedName, $options);

        if ($controller instanceof EntityManagerAwareInterface) {
            $controller->setEntityManager(
                $container->get(EntityManager::class)
            );
        }

        return $controller;
    }
}
