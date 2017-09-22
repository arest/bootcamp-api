<?php
namespace AdminBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query;
use AdminBundle\Model\Filters;
use AdminBundle\Model\Sort;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Doctrine\Common\Util\Inflector;

class CRUDDecorator extends DecoratorRepository
{
    protected $sort;
    protected $filters;
    protected $query_params;
    protected $search_fields;

    /**
     * User by Admin CRUD to retrieve all paginated objects
     *
     * @var result
     **/
    public function getList()
    {
        $entityAlias = 'a';

        try {
            $qb =  $this->repository->getBaseQueryBuilder($entityAlias);            
        } catch (\Exception $e) {
            $qb = $this->repository->createQueryBuilder($entityAlias);
        }

        if (method_exists($this->repository, 'filter')) {
            $qb =  $this->repository->filter($qb, $this->filters );            
        } else {
            $qb = $this->filter($qb);
        }

        if (method_exists($this->repository, 'sort')) {
            $qb =  $this->repository->sort($qb, $this->sort );            
        } else {
            $qb = $this->sort($qb);
        }


        $adapter = new DoctrineORMAdapter($qb);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage($this->filter_bag->get('per_page'));
        $pagerfanta->setCurrentPage($this->filter_bag->get('page'));
        return $pagerfanta;
    }


    public function getEditItem($id)
    {
        if (method_exists($this->repository, 'getEditItem')) {
            return $this->repository->getEditItem($id);
        }
        return $this->repository->find($id);
    }

    public function filter(QueryBuilder $qb)
    {
        if (!$this->filters->search) {
            return $qb;
        }

        if (count($this->search_fields)== 0) {
            return $qb;
        }

        $orX = $qb->expr()->orX();
        foreach ($this->search_fields as $field) {
            $condition = $qb->expr()->like( 
                $field, $qb->expr()->literal('%'.$this->filters->search.'%')
            );
            $orX->add($condition);
        }
        $qb->andWhere($orX);

        return $qb;
    }

    public function sort(QueryBuilder $qb )
    {
        $sort = $this->sort;
        if (isset($sort->field) && $sort->field ) {
            $direction = strtoupper($sort->direction);
            $qb->orderBy( 'a.'.$sort->field, strtoupper($sort->direction) );
        }
        return $qb;
    }

    public function setSort( Sort $sort )
    {
        $this->sort = $sort;
    }

    public function setFilters( Filters $filters )
    {
        $this->filters = $filters;
    }

    public function setFilterBag( $filter_bag )
    {
        $this->filter_bag = $filter_bag;
    }

    public function setSearchFields( array $fields )
    {
        $this->search_fields = $fields;
    }
}