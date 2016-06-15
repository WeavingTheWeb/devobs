<?php

namespace WTW\UserBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;
use WeavingTheWeb\Bundle\FrameworkExtraBundle\Test\WebTestCase;

/**
 * @author Thierry Marianne <thierry.marianne@weaving-the-web.org>
 * @group security-context
 */
class SecurityContextTest extends WebTestCase
{
    protected $router;

    public function setUp()
    {
        $this->client = $this->getClient();
        $this->router = $this->getService('router');
    }

    /**
     * @group security
     * @group isolated-testing
     */
    public function testLogin()
    {
        $url = $this->router->generate('fos_user_security_login');
        $this->client->request('GET', $url);
        $this->assertResponseStatusCodeEquals(200);
    }

    /**
     * @group security
     * @group isolated-testing
     */
    public function testRegister()
    {
        $url = $this->router->generate('fos_user_registration_register');
        $this->client->request('GET', $url);
        $this->assertResponseStatusCodeEquals(200);
    }

    /**
     * @group security
     * @group isolated-testing
     */
    public function testConfirmedRegistration()
    {
        $url = $this->router->generate('fos_user_registration_confirmed');
        $this->client->request('GET', $url);
        $this->assertResponseStatusCodeEquals(302);
    }
}
