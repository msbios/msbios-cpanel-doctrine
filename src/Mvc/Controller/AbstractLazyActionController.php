<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\CPanel\Doctrine\Mvc\Controller;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use MSBios\CPanel\Mvc\Controller\AbstractLazyActionController as DefaultAbstractLazyActionController;
use MSBios\Resource\Entity;
use Zend\Form\FormInterface;
use Zend\Http\PhpEnvironment\Request;
use Zend\Paginator\Paginator;
use Zend\Stdlib\Parameters;
use Zend\View\Model\ViewModel;

/**
 * Class AbstractLazyActionController
 * @package MSBios\CPanel\Doctrine\Mvc\Controller
 */
class AbstractLazyActionController extends DefaultAbstractLazyActionController implements
    EntityManagerAwareInterface
{
    /** @const EVENT_PERSIST_OBJECT */
    const EVENT_PERSIST_OBJECT = 'persist.object';

    /** @const EVENT_MERGE_OBJECT */
    const EVENT_MERGE_OBJECT = 'merge.object';

    /** @const EVENT_REMOVE_OBJECT */
    const EVENT_REMOVE_OBJECT = 'remove.object';

    use EntityManagerAwareTrait;

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function indexAction()
    {
        /** @var EntityRepository $entityRepository */
        $entityRepository = $this->getEntityManager()->getRepository(
            $this->getResourceClassName()
        );

        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $entityRepository->createQueryBuilder('resource');

        /** @var Paginator $paginator */
        $paginator = (new Paginator(
            new DoctrineAdapter(
                new ORMPaginator($queryBuilder)
            )
        ))->setItemCountPerPage($this->getOptions()->get('item_count_per_page'));

        /** @var int $page */
        $page = (int)$this->params()->fromQuery('page');
        if ($page) {
            $paginator->setCurrentPageNumber($page);
        }

        return new ViewModel([
            'paginator' => $paginator,
            'config' => $this->getOptions()
        ]);
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function addAction()
    {
        /** @var int $id */
        if ($id = $this->params()->fromRoute('id')) {
            return $this->redirect()->toRoute(
                $this->getRouteName(), ['action' => 'add']
            );
        }

        /** @var FormInterface $form */
        $form = $this->getFormElement();

        /** @var Request $request */
        $request = $this->getRequest();

        if ($request->isPost()) {

            /** @var Parameters $parameters */
            $parameters = $request->getPost();
            $form->setData($parameters);

            if ($form->isValid()) {

                /** @var Entity $entity */
                $entity = $form->getObject();

                // fire event
                $this->getEventManager()->trigger(
                    self::EVENT_PERSIST_OBJECT, $this, ['entity' => $entity, 'data' => $parameters]
                );

                $this->getEntityManager()->persist($entity);
                $this->getEntityManager()->flush();

                $this->flashMessenger()
                    ->addSuccessMessage('Entity has been create');

                return $this->redirect()->toRoute($this->getRouteName());
            }
        }

        $form->setAttribute(
            'action', $this->url()->fromRoute($this->getRouteName(), ['action' => 'add'])
        );

        return new ViewModel(['form' => $form]);
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
            $this->getResourceClassName(), $id
        );

        if (!$object) {
            return $this->redirect()->toRoute(
                $this->getRouteName()
            );
        }

        /** @var FormInterface $form */
        $form = $this->getFormElement()->bind(clone $object);

        /** @var Request $request */
        $request = $this->getRequest();

        if ($request->isPost()) {

            /** @var array $parameters */
            $parameters = $request->getPost();
            $form->setData($parameters);
            if ($form->isValid()) {

                /** @var Entity $entity */
                $entity = $form->getObject();

                // fire event
                $this->getEventManager()->trigger(
                    self::EVENT_MERGE_OBJECT,
                    $this, ['object' => $object, 'entity' => $entity, 'data' => $parameters]
                );

                $this->getEntityManager()->merge($entity);
                $this->getEntityManager()->flush();

                $this->flashMessenger()
                    ->addSuccessMessage('Entity has been update');

                return $this->redirect()
                    ->toRoute($this->getOptions()->get('route_name'));
            }
        }

        $form->setAttribute(
            'action', $this->url()->fromRoute($this->getRouteName(), ['action' => 'edit', 'id' => $id])
        );

        return new ViewModel([
            'object' => $object, 'form' => $form
        ]);
    }

    /**
     * @return \Zend\Http\Response
     */
    public function dropAction()
    {
        /** @var Entity\ $object */
        $object = $this->entityManager->find(
            $this->getResourceClassName(), $this->params()->fromRoute('id', 0)
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

        return $this->redirect()->toRoute($this->getRouteName());
    }
}