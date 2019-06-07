<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    /*
     * Access without rights
     */
    public function testuserlist()
    {
        $client = static::createClient();
        $client->request('GET', '/user/list');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
}