<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;

use AppBundle\Entity\AccessToken;

class AccessTokenRepository extends EntityRepository
{

}