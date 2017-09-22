<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Author;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AuthorFixture extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();

        for ($i=1; $i <= 50; $i++) { 


            $author = new Author();
            $author->setEmail( $faker->email );
            $author->setFirstName( $faker->firstName );
            $author->setLastName( $faker->lastName );

            $manager->persist( $author);

            $this->addReference('author-'.$i, $author);            
        }

        $manager->flush();

    }

    public function getOrder()
    {
        return 40; // number in which order to load fixtures
    }
}
