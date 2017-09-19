<?php

namespace Tests\AppBundle\Controller;


class AuthorControllerTest extends BaseControllerTest
{
    
    public function testGetSuccess()
    {
        $client = static::createClient();
        $apiKey = $this->getApiUserKey();

        $author = $this->fixtures->getReference('author-1');
        $crawler = $client->request('GET', '/api/author/'.$author->getId(), $apiKey );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('firstName', $data );
        $this->assertArrayHasKey('lastName', $data );
        $this->assertArrayHasKey('email', $data );
        $this->assertArrayHasKey('id', $data );

        $this->assertEquals($data['email'], $author->getEmail() );	
    }
    



    public function testCreateSuccess()
    {
        $apiKey = $this->getApiKey();
        
        $client = static::createClient(array('test',true));

        $author = $this->fixtures->getReference('author-1');

        $params = [
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName,
            'email' => $this->faker->email,
        ];

        $crawler = $client->request('POST', '/api/author?apikey='.$apiKey, $params );

        $this->writeLn("Api Create new Author - Successful response");

        
        $this->assertEquals( 201, $client->getResponse()->getStatusCode() );
        $data = json_decode( $client->getResponse()->getContent(), true);


        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('firstName', $data);
        $this->assertArrayHasKey('lastName', $data);
        $this->assertArrayHasKey('email', $data);

    }


    public function testCreateUnsuccess()
    {
        
        $client = static::createClient(array('test',true));

        $author = $this->fixtures->getReference('author-1');

        $params = [
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName,
            'email' => $this->faker->email,
        ];

        $crawler = $client->request('POST', '/api/quote?apikey=abracadabra', $params );

        $this->writeLn("Api Create new Author - Unsuccessful response");
        
        $this->assertEquals( 500, $client->getResponse()->getStatusCode() );

    }


    public function testListSuccess()
    {
        $client = static::createClient();
        $apiKey = $this->getApiUserKey();

        $crawler = $client->request('GET', '/api/author/list', $apiKey );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertCount(50, $data );
    }


    public function testUpdateSuccess()
    {
        $apiKey = $this->getApiKey();
        
        $client = static::createClient(array('test',true));
        $author = $this->fixtures->getReference('author-1');

        $formData = array(
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName,
            'email' => $author->getEmail(),
        );

        $crawler = $client->request('PUT', '/api/author/'.$author->getId().'?apikey='.$apiKey, $formData, [], []  );

        $this->writeLn("Api Author Update - Successful response");

        $data = json_decode( $client->getResponse()->getContent(), true);

        $this->assertEquals( 202, $client->getResponse()->getStatusCode() );

        $this->assertEquals( $data['email'], $author->getEmail() );

    }


    public function testDeleteSuccess()
    {
        $apiKey = $this->getApiKey();
        $author = $this->fixtures->getReference('author-1');

        $client = static::createClient(array('test',true));

        $crawler = $client->request('DELETE', '/api/author/'.$author->getId().'?apikey='.$apiKey );

        $this->writeLn("Api Author Delete - Successful response");

        $data = json_decode( $client->getResponse()->getContent(), true);

        $this->assertEquals( 202, $client->getResponse()->getStatusCode() );


    }


}
