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

/**
 * Class AbstractLazyActionController
 * @package MSBios\CPanel\Doctrine\Mvc\Controller
 */
class AbstractLazyActionController extends DefaultAbstractLazyActionController
{
    /** @const EVENT_PERSIST_OBJECT */
    const EVENT_PERSIST_OBJECT = 'persist.object';

    /** @const EVENT_MERGE_OBJECT */
    const EVENT_MERGE_OBJECT = 'merge.object';

    /** @const EVENT_REMOVE_OBJECT */
    const EVENT_REMOVE_OBJECT = 'remove.object';

    /**
     * @return DoctrineAdapter
     */
    protected function getPaginatorAdapter()
    {
         /** @var EntityRepository $entityRepository */
         $entityRepository = $this->getEntityManager()
             ->getRepository($this->getResourceClassName());

         /** @var QueryBuilder $queryBuilder */
         $queryBuilder = $entityRepository->createQueryBuilder('resource');

         return new DoctrineAdapter(new ORMPaginator($queryBuilder));
    }

    /**
     * @param array $values
     */
    protected function persistData(array $values)
    {
        /** @var Entity $entity */
        $entity = $this->getFormElement()
            ->getObject();

        // fire event
        $this->getEventManager()->trigger(self::EVENT_PERSIST_OBJECT, $this, [
            'entity' => $entity, 'values' => $values
        ]);

        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * @param $object
     * @param array $values
     */
    protected function mergeData($object, array $values)
    {
        /** @var Entity $entity */
        $entity = $this->getFormElement()
            ->getObject();

        // fire event
        $this->getEventManager()->trigger(self::EVENT_MERGE_OBJECT, $this, [
            'object' => $object,
            'entity' => $entity,
            'values' => $values
        ]);

        $this->getEntityManager()->merge($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * @param $id
     */
    protected function dropData($id)
    {
        /** @var Entity $entity */
        if ($entity = $this->current()) {

            $this->getEventManager()->trigger(self::EVENT_REMOVE_OBJECT, $this, [
                'object' => $entity
            ]);

            $this->getEntityManager()->remove($entity);
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return object
     */
    protected function current()
    {
        return $this->getEntityManager()
            ->find($this->getResourceClassName(), $this->params()->fromRoute('id', 0));
    }

}