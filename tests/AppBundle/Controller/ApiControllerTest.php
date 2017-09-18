<?php

namespace Tests\AppBundle\Controller;

#use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{

	protected function setUp()
    {   
        require_once __DIR__.'/../../../app/AppKernel.php';

        $kernel = new \AppKernel('test', true);
        $kernel->boot();
        $container = $kernel->getContainer();
        $this->faker = $container->get('faker.generator');

        $this->loadFixtures(array(
            'AppBundle\DataFixtures\ORM\AuthorFixture',
            'AppBundle\DataFixtures\ORM\QuoteFixture',
        ));
    }

    public function testRandomSuccess()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api/quote/random');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('author', $data );
        $this->assertArrayHasKey('content', $data );
        $this->assertArrayHasKey('firstName', $data['author'] );
        $this->assertArrayHasKey('lastName', $data['author'] );
        $this->assertArrayNotHasKey('email', $data['author'] );

    }

}
