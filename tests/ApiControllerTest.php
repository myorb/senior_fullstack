<?php

namespace App\Tests\Util;

use App\Entity\Hotel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * @var \App\Entity\Hotel
     */
    private $hotel;

    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->hotel = $this->entityManager
            ->getRepository(Hotel::class)
            ->findOneBy([]);
    }

    public function testGetAverage()
    {
        $this->client->request('GET', '/api/average?hotelId=' . $this->hotel->getId());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testGetReviews()
    {
        $this->client->request('GET', '/api/reviews?hotelId=' . $this->hotel->getId());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testCreateReview()
    {
        $this->client->request(
            'POST',
            '/api/create-review',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json'
            ],
            json_encode(
                [
                    'hotelId' => $this->hotel->getId(),
                    'score' => 5,
                    'comment' => 'This is test comment',
                ]
            )
        );
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            '{"status":"Created!"}',
            $this->client->getResponse()->getContent()
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
