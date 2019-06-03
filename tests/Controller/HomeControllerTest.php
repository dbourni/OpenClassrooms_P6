<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testindex()
    {
        $this->client = static::createClient();
        $this->client->request('GET', '/');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    /*
     * Access without rights
     */
    public function testbackoffice()
    {
        $this->client = static::createClient();
        $this->client->request('GET', '/backoffice');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }
}
