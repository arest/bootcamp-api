<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;

abstract class FilterRepository extends EntityRepository
{

    protected function filterAndSort( array $filters, QueryBuilder $qb ) 
    {
        if (isset($filters['_start'])) {
            $qb->setFirstResult($filters['_start']);
        }
        if (isset($filters['_end'])) {
            $qb->setMaxResults($filters['_end']-$filters['_start']);
        }
        if (isset($filters['_sort']) && isset($filters['_order'])) {
            $qb->addOrderBy( $this->entityAlias.'.'.$filters['_sort'], $filters['_order'] );
        }
        return $qb;
    }


    public function getTotal()
    {
        return $this->getBaseQueryBuilder('a')
                ->select('COUNT(a.id)')
                //->useQueryCache(true)
                //->useResultCache(true, 3600)
                ->getQuery()
                ->getSingleScalarResult()
        ;
    }

}