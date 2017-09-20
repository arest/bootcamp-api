<?php

namespace Tests\AppBundle\Controller;


class QuoteControllerTest extends BaseControllerTest
{

    public function testRandomSuccess()
    {
        $client = static::createClient();
        $apiKey = $this->getApiUserKey();

        $crawler = $client->request('GET', '/api/quote/random', $apiKey);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('author', $data );
        $this->assertArrayHasKey('content', $data );
        $this->assertArrayHasKey('firstName', $data['author'] );
        $this->assertArrayHasKey('lastName', $data['author'] );
        $this->assertArrayNotHasKey('email', $data['author'] );

    }

    
    public function testGetOneSuccess()
    {
        $client = static::createClient();
        $apiKey = $this->getApiUserKey();

        $quote = $this->fixtures->getReference('quote-1');
        $crawler = $client->request('GET', '/api/quote/'.$quote->getId(), $apiKey );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('content', $data );
        $this->assertArrayHasKey('author', $data );
        $this->assertEquals($data['content'], $quote->getContent() );	
    }
    



    public function testCreateSuccess()
    {
        //$headers = $this->getGenericUserWsseHeaders();
        $apiKey = $this->getApiKey();
        
        $client = static::createClient(array('test',true));

        $author = $this->fixtures->getReference('author-1');

        $params = [
            'author' => $author->getId(),
            'content' => $this->faker->sentence,
        ];

        $crawler = $client->request('POST', '/api/quote?apikey='.$apiKey, $params );

        $this->writeLn("Api Create new Quote - Successful response");

        
        $this->assertEquals( 201, $client->getResponse()->getStatusCode() );
        $data = json_decode( $client->getResponse()->getContent(), true);


        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('content', $data);
        $this->assertArrayHasKey('author', $data);

    }


    public function testCreateUnsuccess()
    {
        
        $client = static::createClient(array('test',true));

        $author = $this->fixtures->getReference('author-1');

        $params = [
            'author' => $author->getId(),
            'content' => $this->faker->sentence,
        ];

        $crawler = $client->request('POST', '/api/quote?apikey=abracadabra', $params );

        $this->writeLn("Api Create new Quote - Unsuccessful response");
        
        $this->assertEquals( 500, $client->getResponse()->getStatusCode() );

    }


    public function testListSuccess()
    {
        $client = static::createClient();
        $apiKey = $this->getApiUserKey();

        $crawler = $client->request('GET', '/api/quote', $apiKey );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertCount(100, $data );
    }


    public function testListCustomHeaderSuccess()
    {
        $client = static::createClient();
        $apiKey = $this->getApiKey();

        $headers = [
            'HTTP_X-Apikey' => $apiKey,
        ];

        $crawler = $client->request('GET', '/api/quote', [], [], $headers );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertCount(100, $data );
    }


    public function testListWithFilterSuccess()
    {
        $author = $this->fixtures->getReference('author-1');

        $client = static::createClient();
        $params = $this->getApiUserKey();

        $params['author_id'] = $author->getId();

        $crawler = $client->request('GET', '/api/quote', $params );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertGreaterThan(0, $data );
    }

    public function testUpdateSuccess()
    {
        $apiKey = $this->getApiKey();
        
        $client = static::createClient(array('test',true));
        $quote = $this->fixtures->getReference('quote-1');
        $author = $this->fixtures->getReference('author-1');

        $formData = array(
            'author' => $author->getId(),
            'content' => 'Test 123',
        );

        $crawler = $client->request('PUT', '/api/quote/'.$quote->getId().'?apikey='.$apiKey, $formData, [], []  );

        $this->writeLn("Api Quote Update - Successful response");

        $data = json_decode( $client->getResponse()->getContent(), true);

        $this->assertEquals( 202, $client->getResponse()->getStatusCode() );

        $this->assertEquals( $data['content'], 'Test 123' );
        $this->assertEquals( $data['author']['lastName'], $author->getLastName() );

    }


    public function testDeleteSuccess()
    {
        $apiKey = $this->getApiKey();
        $quote = $this->fixtures->getReference('quote-1');

        $client = static::createClient(array('test',true));

        $crawler = $client->request('DELETE', '/api/quote/'.$quote->getId().'?apikey='.$apiKey );

        $this->writeLn("Api Quote Delete - Successful response");

        $data = json_decode( $client->getResponse()->getContent(), true);

        $this->assertEquals( 202, $client->getResponse()->getStatusCode() );


    }


}
