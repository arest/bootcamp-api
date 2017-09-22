<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Author;
use AppBundle\Entity\Quote;

use Symfony\Component\Validator\Validation;
use AppBundle\Exception\StatusErrorException;
use Doctrine\Common\Collections\ArrayCollection;

class QuoteTest extends \PHPUnit_Framework_TestCase
{

    protected function setUp()
    {

        $this->author = new Author;
        $this->author->setEmail('test@test.it');
        $this->author->setFirstName('Andrea');
        $this->author->setLastName('Restello');

        $this->quote = new Quote;
        $this->quote->setAuthor($this->author);
        $this->quote->setContent('test 123');
    }

    public function testAuthorId() 
    {
        $this->assertSame( $this->quote->getAuthorId(), null );
    }

}