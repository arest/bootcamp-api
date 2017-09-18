<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;

use AppBundle\Entity\Author;

class AuthorRepository extends EntityRepository
{

    public function getBaseQueryBuilder( $entityAlias = 'a')
    {
        $this->entityAlias = $entityAlias;
        return $this
                ->createQueryBuilder($entityAlias)
        ;
    }

}