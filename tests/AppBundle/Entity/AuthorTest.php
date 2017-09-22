<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Author;
use AppBundle\Entity\Quote;

use Symfony\Component\Validator\Validation;
use AppBundle\Exception\StatusErrorException;
use Doctrine\Common\Collections\ArrayCollection;

class AuctionTest extends \PHPUnit_Framework_TestCase
{

    protected function setUp()
    {
        $this->author = new Author;
    }

    public function testFullName() 
    {
        $this->author->setFirstName('Andrea');
        $this->author->setLastName('Restello');

        $this->assertSame( $this->author->getFullName(), 'Andrea Restello' );
    }

}