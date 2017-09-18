<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;

use AppBundle\Entity\Quote;

class QuoteRepository extends EntityRepository
{

    public function getBaseQueryBuilder( $entityAlias = 'q')
    {
        $this->entityAlias = $entityAlias;
        return $this
                ->createQueryBuilder($entityAlias)
        ;
    }
    
}