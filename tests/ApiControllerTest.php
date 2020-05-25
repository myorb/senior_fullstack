<?php

namespace App\Tests\Util;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testGetAverage()
    {
        $client = static::createClient();
        $client->request('GET', '/api/average?hotelId=1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testGetReviews()
    {
        $client = static::createClient();
        $client->request('GET', '/api/reviews?hotelId=1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }
}
