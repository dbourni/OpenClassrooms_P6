<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class SecurityControllerTest extends WebTestCase
{
    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testloginform()
    {
        $client = static::createClient();
        $client->request('GET', '/login');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testlogin()
    {
        $this->logIn();
        $this->client->request('GET', '/figure/new');
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    private function logIn()
    {
        $session = $this->client->getContainer()->get('session');

        $firewallName = 'main';
        $firewallContext = 'main';

        $token = new UsernamePasswordToken('admin@admin.com', null, $firewallName, ['ROLE_ADMIN']);
        $session->set('_security_'.$firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}