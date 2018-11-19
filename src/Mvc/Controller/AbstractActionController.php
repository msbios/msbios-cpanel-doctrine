<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\CPanel\Doctrine\Mvc\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use MSBios\CPanel\Mvc\Controller\AbstractActionController as SimpleAbstractActionController;
use MSBios\CPanel\Mvc\Controller\ActionControllerInterface;
use MSBios\Doctrine\ObjectManagerAwareTrait;
use MSBios\Guard\GuardInterface;
use MSBios\Guard\Resource\Doctrine\BlameableAwareInterface;
use MSBios\Resource\Doctrine\Entity\Layout;
use MSBios\Resource\Doctrine\EntityInterface;
use MSBios\Resource\Doctrine\TimestampableAwareInterface;
use Zend\Config\Config;
use Zend\EventManager\EventManagerInterface;
use Zend\Form\FormInterface;
use Zend\Hydrator\HydratorInterface;
use Zend\Mvc\Controller\AbstractActionController as DefaultAbstractActionController;
use Zend\Mvc\Controller\Plugin\Params;
use Zend\Paginator\Paginator;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Zend\Stdlib\ArrayUtils;
use Zend\Stdlib\Parameters;
use Zend\Stdlib\RequestInterface;
use Zend\View\Model\ViewModel;

/**
 * Class AbstractActionController
 * @package MSBios\CPanel\Doctrine\Mvc\Controller
 */
abstract class AbstractActionController extends DefaultAbstractActionController implements
    ActionControllerInterface,
    GuardInterface,
    ResourceInterface
{

    /** @const EVENT_PERSIST_OBJECT */
    const EVENT_PERSIST_OBJECT = 'persist.object';

    /** @const EVENT_MERGE_OBJECT */
    const EVENT_MERGE_OBJECT = 'merge.object';

    /** @const EVENT_REMOVE_OBJECT */
    const EVENT_REMOVE_OBJECT = 'remove.object';

    use ObjectManagerAwareTrait;

    /** @var FormInterface */
    protected $form;

    /**
     * AbstractActionController constructor.
     * @param ObjectManager $dem
     * @param FormInterface $form
     */
    public function __construct(ObjectManager $dem, FormInterface $form)
    {
        $this->setObjectManager($dem);
        $this->form = $form;
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getRepository()
    {
        return $this->getObjectManager()
            ->getRepository(Layout::class);
    }

    /**
     * @return string
     */
    protected function getMatchedRouteName()
    {
        return $this
            ->getEvent()
            ->getRouteMatch()
            ->getMatchedRouteName();
    }

    /**
     * @param array $variables
     * @return ViewModel
     */
    protected function createViewModel(array $variables = [])
    {
        return new ViewModel(ArrayUtils::merge([
            'form' => $this->form,
            'matchedRouteName' => $this->getMatchedRouteName(),
            'resourceId' => $this->getResourceId()
        ], $variables));
    }

    /**
     * @return mixed
     */
    public function indexAction()
    {
        /** @var Params $params */
        $params = $this->params();

        /** @var string $matchedRouteName */
        $matchedRouteName = $this->getMatchedRouteName();

        /** @var FormInterface $form */
        $form = $this->form;
        $form->setAttribute(
            'action',
            $this->url()->fromRoute($matchedRouteName, ['action' => 'add'])
        );

        /** @var ObjectRepository $repository */
        $repository = $this->getRepository();

        /** @var Paginator $paginator */
        $paginator = $repository
            ->fetchAll();

        $paginator
            ->setItemCountPerPage(
                (int)$params->fromQuery('limit', SimpleAbstractActionController::DEFAULT_ITEM_COUNT_PER_PAGE)
            )
            ->setCurrentPageNumber(
                (int)$params->fromQuery('page', SimpleAbstractActionController::DEFAULT_CURRENT_PAGE_NUMBER)
            );

        return $this->createViewModel([
            'paginator' => $paginator,
            'form' => $form
        ]);
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function addAction()
    {
        /** @var string $matchedRouteName */
        $matchedRouteName = $this->getEvent()
            ->getRouteMatch()
            ->getMatchedRouteName();

        /** @var int $id */
        if ($id = $this->params()->fromRoute('id')) {
            return $this->redirect()->toRoute($matchedRouteName, ['action' => 'add']);
        }

        /** @var FormInterface $form */
        $form = $this->getFormElementManager()->get(get_called_class());

        /** @var RequestInterface $request */
        $request = $this->getRequest();

        if ($request->isPost()) {
            if ($form->getHydrator() instanceof DoctrineObject) {
                $form->setObject($this->getObjectPrototype());
            }

            /** @var Parameters $parameters */
            $parameters = $request->getPost();
            $form->setData($parameters);

            /** @var EventManagerInterface $eventManager */
            $eventManager = $this->getEventManager();

            if ($form->isValid()) {

                /** @var EntityInterface $entity */
                $entity = $form->getData();

                /** @var ObjectManager $dem */
                $dem = $this->getObjectManager();

                if (! $entity instanceof EntityInterface) {

                    /** @var HydratorInterface $doh */
                    $doh = $this->getHydratorManager()
                        ->get(DoctrineObject::class);

                    /** @var EntityInterface $entity */
                    $entity = $doh->hydrate($entity, $this->getObjectPrototype());
                }

                // TODO: Move to event listeners

                if ($entity instanceof TimestampableAwareInterface) {
                    // TODO: move to event
                    $entity->setCreatedAt(new \DateTime('now'));
                    $entity->setModifiedAt(new \DateTime('now'));
                }

                if ($entity instanceof BlameableAwareInterface) {
                    // TODO: move to event
                    $entity->setCreator($this->identity());
                    $entity->setEditor($entity->getCreator());
                }

                // TODO: Move to event listeners

                // fire event
                $eventManager->trigger(self::EVENT_PERSIST_OBJECT, $this, [
                    'entity' => $entity, 'data' => $parameters
                ]);

                $dem->persist($entity);
                $dem->flush();

                $this->flashMessenger()
                    ->addSuccessMessage('Entity has been create');

                return $this->redirect()->toRoute($matchedRouteName);
            } else {
                $eventManager->trigger(self::EVENT_VALIDATE_ERROR, $this, [
                    'messages' => $form->getMessages()
                ]);
            }
        }

        $form->setAttribute(
            'action',
            $this->url()->fromRoute($matchedRouteName, ['action' => 'add'])
        );

        return new ViewModel([
            'form' => $form,
            'config' => new Config([
                'resource' => $this->getResourceId(),
                'route_name' => $matchedRouteName
            ])
        ]);
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function editAction()
    {
        /** @var int $id */
        $id = (int)$this->params()->fromRoute('id', 0);

        /** @var ObjectManager $dem */
        $dem = $this->getObjectManager();

        /** @var Object $object */
        $object = $dem->find(get_class($this->getObjectPrototype()), $id);

        /** @var string $matchedRouteName */
        $matchedRouteName = $this->getEvent()
            ->getRouteMatch()
            ->getMatchedRouteName();

        if (! $object) {
            return $this->redirect()->toRoute(
                $matchedRouteName
            );
        }

        /** @var FormInterface $form */
        $form = $this->getFormElementManager()->get(get_called_class());

        /** @var HydratorInterface $doh */
        $doh = $this->getHydratorManager()->get(DoctrineObject::class);

        if (! $form->getHydrator() instanceof DoctrineObject) {
            $form->setData($doh->extract($object));
        } else {
            $form->bind(clone $object);
        }

        /** @var RequestInterface $request */
        $request = $this->getRequest();

        if ($request->isPost()) {

            /** @var Parameters $parameters */
            $parameters = $request->getPost();
            $form->setData($parameters);

            /** @var EventManagerInterface $eventManager */
            $eventManager = $this->getEventManager();

            if ($form->isValid()) {

                /** @var EntityInterface $entity */
                $entity = $form->getData();

                if (! $entity instanceof EntityInterface) {
                    /** @var EntityInterface $entity */
                    $entity = $doh->hydrate($entity, clone $object);
                }

                if ($entity instanceof TimestampableAwareInterface) {
                    // TODO: Move to event listeners
                    $entity->setModifiedAt(new \DateTime('now'));
                }

                if ($entity instanceof BlameableAwareInterface) {
                    // TODO: move to event
                    $entity->setEditor($this->identity());
                }

                $eventManager->trigger(self::EVENT_MERGE_OBJECT, $this, [
                    'object' => $object, 'entity' => $entity, 'data' => $parameters
                ]);

                $dem->merge($entity);
                $dem->flush();

                $this->flashMessenger()
                    ->addSuccessMessage('Entity has been update');

                return $this->redirect()->toRoute(
                    $matchedRouteName
                );
            } else {
                $eventManager->trigger(self::EVENT_VALIDATE_ERROR, $this, [
                    'object' => $object, 'messages' => $form->getMessages()
                ]);
            }
        }

        $form->setAttribute(
            'action',
            $this->url()->fromRoute($matchedRouteName, ['action' => 'edit', 'id' => $id])
        );

        return new ViewModel([
            'object' => $object,
            'form' => $form,
            'config' => new Config([
                'resource' => $this->getResourceId(),
                'route_name' => $matchedRouteName
            ])
        ]);
    }

    /**
     * @return \Zend\Http\Response
     */
    public function dropAction()
    {
        /** @var ObjectManager $dem */
        $dem = $this->getObjectManager();

        /** @var EntityInterface $object */
        $object = $dem->find(
            get_class($this->getObjectPrototype()),
            $this->params()->fromRoute('id', 0)
        );

        /** @var int $id */
        if ($object) {
            $this->getEventManager()->trigger(self::EVENT_REMOVE_OBJECT, $this, [
                'object' => $object
            ]);

            $dem->remove($object);
            $dem->flush();

            $this->flashMessenger()
                ->addSuccessMessage('Entity has been removed');
        }

        return $this->redirect()->toRoute(
            $this->getEvent()->getRouteMatch()->getMatchedRouteName()
        );
    }
}
