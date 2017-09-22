<?php
namespace Tests\AppBundle\Form;

use AppBundle\Form\Type\AuthorFormType;
use AppBundle\Entity\Author;
use AppBundle\Entity\Quote;
use Symfony\Component\Form\Test\TypeTestCase;

class AuthorFormTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $author = new Author();
        $author->setFirstName('John');
        $author->setLastName('Doe');
        $author->setEmail('test@test.it');

        $formData = array(
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'test@test.it',
        );

        $form = $this->factory->create(AuthorFormType::class);


        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        
        $form_data = $form->getData();

        $this->assertEquals($author, $form_data);

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
