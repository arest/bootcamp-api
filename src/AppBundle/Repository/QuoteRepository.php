<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

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

    public function getRandom() 
    {
    	return $this->getBaseQueryBuilder()
                ->select('q, a')
                ->addSelect('RAND() as HIDDEN rand')
                ->leftJoin( 'q.author', 'a')
                ->setMaxResults(1)
            	->getQuery()
            	->getOneOrNullResult()
         ;
    }

    public function getAll() 
    {
        return $this->getBaseQueryBuilder()
                ->select('q, a')
                ->leftJoin( 'q.author', 'a')
                ->getQuery()
                ->getResult();           
    }
    
}