<?php

namespace AdminBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * the Decorator MUST implement the EntityRepository contract, this is the key-feature
 * of this design pattern. If not, this is no longer a Decorator but just a dumb
 * wrapper.
 */

/**
 * class Decorator
 */
abstract class DecoratorRepository
{
    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * You must type-hint the repository component :
     * It ensures you can call renderData() in the subclasses !
     *
     * @param EntityRepository $wrappable
     */
    public function __construct(EntityRepository $wrappable)
    {
        $this->repository = $wrappable;
    }
}
