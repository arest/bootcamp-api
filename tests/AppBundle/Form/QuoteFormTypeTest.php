<?php
namespace Tests\AppBundle\Form;

use AppBundle\Form\Type\QuoteFormType;
use AppBundle\Entity\Author;
use AppBundle\Entity\Quote;
use Symfony\Component\Form\Test\TypeTestCase;

class QuoteFormTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $author = new Author();
        $author->setFirstName('John');
        $author->setLastName('Doe');

        $quote = new Quote();
        $quote->setAuthor($author);
        $quote->setContent('test 123');

        $formData = array(
            'author' => $author,
            'content' => "test 123",
        );

        $form = $this->factory->create(QuoteFormType::class);


        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        
        $form_data = $form->getData();

        $this->assertEquals($quote, $form_data);

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
