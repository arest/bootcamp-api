<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use AdminBundle\Entity\Permission;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PermissionFixture extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {

        $item = new Permission;
        $item->setName('All');
        $item->setRoutes( $this->getRoutes() );

        $manager->persist($item);
        $manager->flush();

        $this->addReference('permission-all', $item);


    }

    protected function getRoutes() {
        $collection = $this->container->get('router')->getRouteCollection();
        $allRoutes = $collection->all();
        $choices = [];

        foreach ($allRoutes as $route => $params) 
        {
            if ( false !== strpos($route, '_app_crud_') ) {
                $choices[] = $route;
            }
        }
        ksort($choices);
        return $choices;
    }


    public function getOrder()
    {
        return 10; // number in which order to load fixtures
    }
}
