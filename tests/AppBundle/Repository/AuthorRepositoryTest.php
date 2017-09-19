<?php
namespace Tests\AppBundle\Repository;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use AppBundle\Entity\Author;

class AuthorRepositoryTest extends WebTestCase
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


    public function testGetAll()
    {
        $items = $this->em
            ->getRepository('AppBundle:Author')
            ->getAll()
        ;

        $this->assertCount( 50, $items );
    }


    public function testGetTotal()
    {
        $result = $this->em
            ->getRepository('AppBundle:Author')
            ->getTotal()
        ;

        $this->assertEquals( 50, $result );
    }

    public function testGetAllWithFilters()
    {

        $items = $this->em
            ->getRepository('AppBundle:Author')
            ->getAll( [
                '_start' => 0,
                '_end' => 10,

            ])
        ;

        $this->assertCount( 10, $items );
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