<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\CPanel\Doctrine\Mvc\Controller;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use MSBios\CPanel\Doctrine\Initializer;
use MSBios\CPanel\Mvc\Controller\AbstractLazyActionController as DefaultAbstractLazyActionController;
use MSBios\Resource\Entity;
use Zend\Config\Config;
use Zend\Form\FormInterface;
use Zend\Paginator\Paginator;
use Zend\Stdlib\Parameters;
use Zend\Stdlib\RequestInterface;
use Zend\View\Model\ViewModel;

/**
 * Class AbstractLazyActionController
 * @package MSBios\CPanel\Doctrine\Mvc\Controller
 */
abstract class AbstractLazyActionController extends DefaultAbstractLazyActionController implements
    Initializer\DoctrineHydratorAwareInterface,
    Initializer\EntityManagerAwareInterface
{
    /** @const EVENT_PERSIST_OBJECT */
    const EVENT_PERSIST_OBJECT = 'persist.object';

    /** @const EVENT_MERGE_OBJECT */
    const EVENT_MERGE_OBJECT = 'merge.object';

    /** @const EVENT_REMOVE_OBJECT */
    const EVENT_REMOVE_OBJECT = 'remove.object';

    use Initializer\DoctrineHydratorAwareTrait;
    use Initializer\EntityManagerAwareTrait;

    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        /** @var ObjectRepository $entityRepository */
        $entityRepository = $this->getEntityManager()->getRepository(
            get_class($this->getObjectPrototype())
        );

        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $entityRepository->createQueryBuilder('resource');

        /** @var Paginator $paginator */
        $paginator = (new Paginator(
            new DoctrineAdapter(
                new ORMPaginator($queryBuilder)
            )
        ))->setItemCountPerPage(self::DEFAULT_ITEM_COUNT_PER_PAGE);

        /** @var int $page */
        $page = (int)$this->params()->fromQuery('page');
        if ($page) {
            $paginator->setCurrentPageNumber($page);
        }

        return new ViewModel([
            'paginator' => $paginator,
            'config' => new Config([
                'resource' => $this->getResourceId(),
                'route_name' => $this->getEvent()
                    ->getRouteMatch()
                    ->getMatchedRouteName()
            ])
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
            return $this->redirect()->toRoute(
                $matchedRouteName, ['action' => 'add']
            );
        }

        /** @var FormInterface $form */
        $form = $this->getForm();

        /** @var RequestInterface $request */
        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setObject($this->getObjectPrototype());

            /** @var Parameters $parameters */
            $parameters = $request->getPost();
            $form->setData($parameters);

            if ($form->isValid()) {

                $entity = $form->getData();

                // TODO: need move to listener
                $entity->setCreator($this->identity());
                $entity->setEditor($entity->getCreator());

                // fire event
                $this->getEventManager()->trigger(
                    self::EVENT_PERSIST_OBJECT, $this, ['entity' => $entity, 'data' => $parameters]
                );

                $this->getEntityManager()->persist($entity);
                $this->getEntityManager()->flush();

                $this->flashMessenger()
                    ->addSuccessMessage('Entity has been create');

                return $this->redirect()->toRoute($matchedRouteName);
            } else {
                $this->getEventManager()->trigger(
                    self::EVENT_VALIDATE_ERROR, $this, [
                        'messages' => $form->getMessages()
                    ]
                );
            }
        }

        $form->setAttribute(
            'action', $this->url()->fromRoute($matchedRouteName, ['action' => 'add'])
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

        /** @var Object $object */
        $object = $this->getEntityManager()->find(
            get_class($this->getObjectPrototype()), $id
        );

        /** @var string $matchedRouteName */
        $matchedRouteName = $this->getEvent()
            ->getRouteMatch()
            ->getMatchedRouteName();

        if (!$object) {
            return $this->redirect()->toRoute(
                $matchedRouteName
            );
        }

        /** @var FormInterface $form */
        $form = $this->getForm()->bind($object);

        /** @var RequestInterface $request */
        $request = $this->getRequest();

        if ($request->isPost()) {

            /** @var Parameters $parameters */
            $parameters = $request->getPost();
            $form->setData($parameters);

            if ($form->isValid()) {

                /** @var Entity $entity */
                $entity = $form->getData();

                // TODO: need move to listener
                $entity->setEditor($this->identity());

                // fire event
                $this->getEventManager()->trigger(
                    self::EVENT_MERGE_OBJECT,
                    $this, ['object' => $object, 'entity' => $entity, 'data' => $parameters]
                );

                $this->getEntityManager()->merge($entity);
                $this->getEntityManager()->flush();

                $this->flashMessenger()
                    ->addSuccessMessage('Entity has been update');

                return $this->redirect()->toRoute(
                    $matchedRouteName
                );
            } else {
                $this->getEventManager()->trigger(
                    self::EVENT_VALIDATE_ERROR, $this, [
                        'object' => $object,
                        'messages' => $form->getMessages()
                    ]
                );
            }
        }

        $form->setAttribute(
            'action', $this->url()->fromRoute($matchedRouteName, ['action' => 'edit', 'id' => $id])
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
        /** @var Entity $object */
        $object = $this->entityManager->find(
            get_class($this->getObjectPrototype()),
            $this->params()->fromRoute('id', 0)
        );

        /** @var int $id */
        if ($object) {

            // fire event
            $this->getEventManager()->trigger(
                self::EVENT_REMOVE_OBJECT, $this, ['object' => $object]
            );

            $this->getEntityManager()->remove($object);
            $this->getEntityManager()->flush();

            $this->flashMessenger()
                ->addSuccessMessage('Entity has been removed');
        }

        return $this->redirect()->toRoute(
            $this->getEvent()->getRouteMatch()->getMatchedRouteName()
        );
    }
}