<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\CPanel\Doctrine\Mvc\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use DoctrineModule\Persistence\ProvidesObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use MSBios\CPanel\Form\SearchForm;
use MSBios\CPanel\Mvc\Controller\AbstractActionController as SimpleAbstractActionController;
use MSBios\CPanel\Mvc\Controller\ActionControllerInterface;
use MSBios\Guard\GuardInterface;
use MSBios\Guard\Resource\Doctrine\BlameableAwareInterface;
use MSBios\Resource\Doctrine\EntityInterface;
use MSBios\Resource\Doctrine\TimestampableAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Form\FormInterface;
use Zend\Hydrator\HydratorInterface;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\Mvc\Controller\AbstractActionController as DefaultAbstractActionController;
use Zend\Mvc\Controller\Plugin\Params;
use Zend\Paginator\Paginator;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Zend\Stdlib\ArrayUtils;
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

    /** @const ITEM_COUNT_PER_PAGE */
    const ITEM_COUNT_PER_PAGE = SimpleAbstractActionController::DEFAULT_ITEM_COUNT_PER_PAGE;

    /** @const CURRENT_PAGE_NUMBER */
    const CURRENT_PAGE_NUMBER = SimpleAbstractActionController::DEFAULT_CURRENT_PAGE_NUMBER;

    use ProvidesObjectManager;

    /** @var FormInterface */
    protected $form;

    /** @var ObjectRepository */
    protected $repository;

    /** @var SearchForm */
    protected $search;

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
     * @return ObjectRepository
     */
    protected function getRepository()
    {
        if (! $this->repository instanceof ObjectRepository) {
            /** @var EntityInterface $entity */
            $entity = static::factory();
            $this->repository = $this
                ->getObjectManager()
                ->getRepository(get_class($entity));
        }

        return $this->repository;
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

        $this->search = (new SearchForm)
            ->setAttribute('action', $this->url()->fromRoute($matchedRouteName))
            ->setData($this->params()->fromQuery());

        /** @var Paginator $paginator */
        $paginator = $this
            ->getRepository()
            ->fetchAll($this);

        $paginator
            ->setItemCountPerPage((int)$params->fromQuery('limit', self::ITEM_COUNT_PER_PAGE))
            ->setCurrentPageNumber((int)$params->fromQuery('page', self::CURRENT_PAGE_NUMBER));

        return $this->createViewModel([
            'search' => $this->search,
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

        /** @var FormInterface $form */
        $form = $this->form;
        $form->setAttribute(
            'action',
            $this->url()->fromRoute($matchedRouteName, ['action' => 'add'])
        );

        if ($this->getRequest()->isPost()) {

            /** @var array $argv */
            $argv = ['object' => static::factory()];

            if ($form->getHydrator() instanceof DoctrineObject) {
                $form->setObject($argv['object']);
            }

            $argv['data'] = $this->params()->fromPost();
            $form->setData($argv['data']);

            /** @var EventManagerInterface $eventManager */
            $eventManager = $this->getEventManager();

            if ($form->isValid()) {
                $argv['entity'] = $form->getData();

                /** @var ObjectManager $dem */
                $dem = $this->getObjectManager();

                if (! $argv['entity'] instanceof EntityInterface) {
                    /** @var EntityInterface $entity */
                    $argv['entity'] = (new DoctrineObject($dem))
                        ->hydrate($argv['entity'], static::factory());
                }

                if ($argv['entity'] instanceof BlameableAwareInterface) {
                    $argv['entity']
                        ->setCreator($this->identity())
                        ->setEditor($argv['entity']->getCreator());
                }

                $eventManager
                    ->trigger(self::EVENT_PERSIST_OBJECT, $this, $argv);

                $this
                    ->getRepository()
                    ->save($argv['entity']);

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

                $this
                    ->getRepository()
                    ->save($values);

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
