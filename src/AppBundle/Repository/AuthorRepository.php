<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;

use AppBundle\Entity\Author;

class AuthorRepository extends FilterRepository
{

    public function getBaseQueryBuilder( $entityAlias = 'a')
    {
        $this->entityAlias = $entityAlias;
        return $this
                ->createQueryBuilder($entityAlias)
        ;
    }

    public function getAll( $filters ) 
    {
        $qb = $this->getBaseQueryBuilder();
        $qb = $this->filterAndSort( $filters, $qb );

        return $qb->getQuery()
                ->getResult()
        ;           
    }

}