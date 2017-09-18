<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use AdminBundle\Entity\PermissionGroup;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PermissionGroupFixture extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {

        $item = new PermissionGroup;
        $item->setName('SuperAdmin');
        $item->addPermission( $this->getReference('permission-all') );

        $manager->persist($item);
        $manager->flush();

        $this->addReference('permission-group-all', $item);

    }

    public function getOrder()
    {
        return 20; // number in which order to load fixtures
    }
}
