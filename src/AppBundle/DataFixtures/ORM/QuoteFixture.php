<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Quote;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class QuoteFixture extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();

        for ($i=1; $i <= 100; $i++) { 

            $quote = new Quote();

            $author_id = $faker->numberBetween(1,50);
            $quote->setAuthor( $this->getReference('author-'.$author_id) );
            $quote->setContent( $faker->sentence );

            $manager->persist( $quote );

            $this->addReference('quote-'.$i, $quote);            
        }

        $manager->flush();

    }

    public function getOrder()
    {
        return 50; // number in which order to load fixtures
    }
}
