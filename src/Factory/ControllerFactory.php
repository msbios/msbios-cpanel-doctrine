<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\CPanel\Doctrine\Factory;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use MSBios\CPanel\Exception\ServiceNotCreatedException;
use Zend\Form\FormElementManager\FormElementManagerV3Polyfill;
use Zend\Form\FormInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ControllerFactory
 * @package MSBios\CPanel\Doctrine\Factory
 */
class ControllerFactory implements FactoryInterface
{
    /**
     * @inheritdoc
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return mixed|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var FormElementManagerV3Polyfill $formElementManager */
        $formElementManager = $container
            ->get('FormElementManager');

        if (! $formElementManager->has($requestedName)) {
            throw new ServiceNotCreatedException(
                sprintf("You need to define a form alias for the controller %s.", $requestedName)
            );
        }

        /** @var FormInterface $form */
        $form = $formElementManager
            ->get($requestedName);

        return new $requestedName($container->get(EntityManager::class), $form);
    }
}
