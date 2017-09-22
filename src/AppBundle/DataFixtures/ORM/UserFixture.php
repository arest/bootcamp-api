<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserFixture extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();

        $admin = new User();
        $admin->setUsername('admin');
        $admin->setEnabled(1);
        $admin->setEmail('andrea.restello@gmail.com');
        $admin->setPlainPassword('admin');
        $admin->setRoles( array('ROLE_ADMIN') );
        $admin->addPermissionGroup( $this->getReference('permission-group-all') );
        $admin->setApiKey(  '4382048328432084932s' );

        $manager->persist($admin);
        

        $user = new User();
        $user->setUsername('apiuser');
        $user->setEnabled(1);
        $user->setEmail('andrea_restello@yahoo.it');
        $user->setPlainPassword('user');
        $user->setRoles( array('ROLE_API') );
        $user->setApiKey(  '123456789abcdefghilmnopqrstuvz' );

        $manager->persist($user);

        $manager->flush();

        $this->addReference('admin-user', $admin);
        $this->addReference('api-user', $user);

    }

    public function getOrder()
    {
        return 30; // number in which order to load fixtures
    }
}
