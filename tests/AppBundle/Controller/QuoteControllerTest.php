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
        $apiKey = $this->getApiUserKey();
        $headers = [];
        
        $client = static::createClient(array('test',true));

        $author = $this->fixtures->getReference('author-1');

        $params = array( 
            'author' => $author->getId(),
            'content' => $this->faker->sentence,
        );

        $crawler = $client->request('POST', '/api/quote', $params, $apiKey, $headers );

        $this->writeLn("Api Create new Quote - Successful response");

        $this->assertEquals( 201, $client->getResponse()->getStatusCode() );
        
        $data = json_decode( $client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('content', $data);
        $this->assertArrayHasKey('author', $data);

    }

}
