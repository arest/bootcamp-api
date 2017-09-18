<?php
// tests/AppBundle/Repository/AuctionRepositoryTest.php
namespace Tests\AppBundle\Repository;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use AppBundle\Entity\Quote;

class QuoteRepositoryTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        require_once __DIR__.'/../../../app/AppKernel.php';

        $kernel = new \AppKernel('test', true);
        $kernel->boot();
        $container = $kernel->getContainer();

        $this->em = $container
            ->get('doctrine')
            ->getManager();

        $this->fixtures = $this->loadFixtures(array(
            'AppBundle\DataFixtures\ORM\AuthorFixture',
            'AppBundle\DataFixtures\ORM\QuoteFixture',
        ))->getReferenceRepository();
    }

    public function testGetRandom()
    {
        $item = $this->em
            ->getRepository('AppBundle:Quote')
            ->getRandom()
        ;

        $this->assertInstanceOf( Quote::class, $item );
    }


    public function testGetAll()
    {
        $items = $this->em
            ->getRepository('AppBundle:Quote')
            ->getAll()
        ;

        $this->assertCount( 100, $items );
    }


    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null; // avoid memory leaks
    }
}