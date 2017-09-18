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

    public function getRandom() {
    	return $this->getBaseQueryBuilder()
                ->select($entityAlias.', a')
                ->addSelect('RAND() as HIDDEN rand')
                ->leftJoin( 'q.author', 'a')
            	->getQuery()
            	->getOneOrNullResult()
         ;
    }
    
}