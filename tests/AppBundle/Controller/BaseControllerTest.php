<?php
namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

abstract class BaseControllerTest extends WebTestCase
{

    protected $client = null;


    protected function setUp()
    {   
        require_once __DIR__.'/../../../app/AppKernel.php';

        $kernel = new \AppKernel('test', true);
        $kernel->boot();
        $container = $kernel->getContainer();
        $this->faker = $container->get('faker.generator');

        $this->fixtures = $this->loadFixtures([
            'AppBundle\DataFixtures\ORM\PermissionFixture',
            'AppBundle\DataFixtures\ORM\PermissionGroupFixture',
            'AppBundle\DataFixtures\ORM\UserFixture',
            'AppBundle\DataFixtures\ORM\AuthorFixture',
            'AppBundle\DataFixtures\ORM\QuoteFixture',
        ])->getReferenceRepository();
    }

    protected function writeLn($string)
    {
        fwrite(STDERR, print_r($string."\n", true));
    }

    protected function getApiUserKey() {
        return [
            'apikey' => $this->getApiKey(),
        ];
    }

    protected function getApiKey() {
        return $this->fixtures->getReference('api-user')->getApiKey();
    }


}