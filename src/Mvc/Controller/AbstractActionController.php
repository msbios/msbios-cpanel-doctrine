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
use MSBios\Resource\Doctrine\EntityInterface;
use MSBios\Resource\Doctrine\TimestampableAwareInterface;
use Zend\Config\Config;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Form\FormInterface;
use Zend\Hydrator\HydratorInterface;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\Mvc\Controller\AbstractActionController as DefaultAbstractActionController;
use Zend\Mvc\Controller\Plugin\Params;
use Zend\Mvc\MvcEvent;
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

    /** @const EVENT_VALIDATE_ERROR */
    const EVENT_VALIDATE_ERROR = 'validate.error';

    use ObjectManagerAwareTrait;

    /** @var FormInterface */
    protected $form;

    /**
     * @return mixed
     */
    abstract protected static function factory();

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
     * @param MvcEvent $e
     * @return mixed
     */
    public function onDispatch(MvcEvent $e)
    {
        /** @var ActionControllerInterface $target */
        $target = $e->getTarget();

        /** @var EventManagerInterface $eventManager */
        $eventManager = $target->getEventManager();

        $eventManager->attach(self::EVENT_PERSIST_OBJECT, [$this, '__onPersistObject']);
        $eventManager->attach(self::EVENT_MERGE_OBJECT, [$this, '__onMergeObject']);

        return parent::onDispatch($e);
    }

    /**
     * @param EventInterface $event
     */
    public function __onPersistObject(EventInterface $event)
    {
        /** @var EntityInterface $entity */
        $entity = $event->getParam('entity');

        if (! $entity instanceof EntityInterface) {
            return;
        }

        if ($entity instanceof TimestampableAwareInterface) {
            $entity
                ->setCreatedAt(new \DateTime)
                ->setModifiedAt(new \DateTime);
        }

        if ($entity instanceof BlameableAwareInterface) {
            $entity
                ->setCreator($this->identity())
                ->setEditor($entity->getCreator());
        }
    }

    /**
     * @param EventInterface $event
     */
    public function __onMergeObject(EventInterface $event)
    {
        /** @var EntityInterface $entity */
        $entity = $event->getParam('entity');

        if (! $entity instanceof EntityInterface) {
            return;
        }

        if ($entity instanceof TimestampableAwareInterface) {
            $entity->setModifiedAt(new \DateTime);
        }

        if ($entity instanceof BlameableAwareInterface) {
            $entity->setEditor($entity->getCreator());
        }
    }

    /**
     * @return ObjectRepository
     */
    protected function getRepository()
    {
        /** @var EntityInterface $entity */
        $entity = static::factory();
        return $this->getObjectManager()
            ->getRepository(get_class($entity));
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
        $matchedRouteName = $this->getMatchedRouteName();

        /** @var int $id */
        if ($id = $this->params()->fromRoute('id')) {
            return $this->redirect()->toRoute($matchedRouteName, ['action' => 'add']);
        }

        /** @var FormInterface $form */
        $form = $this->form;

        /** @var RequestInterface $request */
        $request = $this->getRequest();

        if ($request->isPost()) {
            if ($form->getHydrator() instanceof DoctrineObject) {
                $form->setObject(static::factory());
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
                    $doh = new DoctrineObject($dem);

                    /** @var EntityInterface $entity */
                    $entity = $doh->hydrate($entity, static::factory());
                }

                if ($entity instanceof BlameableAwareInterface) {
                    $entity
                        ->setCreator($this->identity())
                        ->setEditor($entity->getCreator());
                }

                // fire event
                $eventManager->trigger(self::EVENT_PERSIST_OBJECT, $this, [
                    'entity' => $entity, 'data' => $parameters
                ]);

                $dem->persist($entity);
                $dem->flush();

                $this
                    ->flashMessenger()
                    ->addSuccessMessage('Entity has been create');

                return $this->redirect()
                    ->toRoute($matchedRouteName);
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

        return $this->createViewModel();
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function editAction()
    {
        /** @var int $id */
        $id = (int)$this->params()
            ->fromRoute('id', 0);

        /** @var string $matchedRouteName */
        $matchedRouteName = $this->getMatchedRouteName();

        if (! $id) {
            return $this->redirect()
                ->toRoute($matchedRouteName, ['action' => 'add']);
        }

        /** @var ObjectManager $dem */
        $dem = $this->getObjectManager();

        /** @var Object $object */
        $object = $dem->find(get_class(static::factory()), $id);

        if (! $object) {
            $this->flashMessenger()
                ->addInfoMessage('Entity not found');
            return $this->redirect()
                ->toRoute($matchedRouteName);
        }

        /** @var FormInterface $form */
        $form = $this->form;
        $form->setAttribute(
            'action',
            $this->url()->fromRoute($matchedRouteName, ['action' => 'edit', 'id' => $object->getId()])
        );

        /** @var EventManagerInterface $eventManager */
        $eventManager = $this->getEventManager();

        /** @var ObjectManager $dem */
        $dem = $this->getObjectManager();

        /** @var HydratorInterface $doh */
        $doh = new DoctrineObject($dem);

        if ($form->getHydrator() instanceof DoctrineObject) {
            $form->bind(clone $object);
        } else {
            $form->setData($doh->extract($object));
        }

        if ($this->getRequest()->isPost()) {
            if ($object instanceof InputFilterAwareInterface) {
                $form->setInputFilter($object->getInputFilter());
            }

            /** @var array $data */
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {

                /** @var array|EntityInterface $values */
                $values = $form->getData();

                if (! $values instanceof EntityInterface) {
                    /** @var EntityInterface $entity */
                    $values = $doh->hydrate($values, clone $object);
                }

                if ($values instanceof TimestampableAwareInterface) {
                    $values->setModifiedAt(new \DateTime);
                }

                if ($values instanceof BlameableAwareInterface) {
                    $values->setEditor($this->identity());
                }

                $eventManager->trigger(self::EVENT_MERGE_OBJECT, $this, [
                    'object' => $object, 'data' => $data, 'entity' => $values
                ]);

                $dem->merge($values);
                $dem->flush();

                $this->flashMessenger()
                    ->addSuccessMessage('Entity has been update');

                return $this->redirect()
                    ->toRoute($matchedRouteName);
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

        return $this->createViewModel([
            'object' => $object
        ]);
    }

    /**
     * @return \Zend\Http\Response
     */
    public function dropAction()
    {
        /** @var int $id */
        $id = $this->params()->fromRoute('id', 0);

        /** @var ObjectManager $dem */
        $dem = $this->getObjectManager();

        /** @var EntityInterface $object */
        $object = $dem->find(get_class(static::factory()), $id);

        /** @var int $id */
        if ($object) {
            $this->getEventManager()
                ->trigger(self::EVENT_REMOVE_OBJECT, $this, ['object' => $object]);

            $this->flashMessenger()
                ->addSuccessMessage("Row '{$id}' was deleted.");

            $dem->remove($object);
            $dem->flush();
        }

        return $this->redirect()
            ->toRoute($this->getMatchedRouteName());
    }
}
