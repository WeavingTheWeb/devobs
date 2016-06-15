<?php

namespace WTW\UserBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;
use WeavingTheWeb\Bundle\FrameworkExtraBundle\Test\WebTestCase;

/**
 * @author Thierry Marianne <thierry.marianne@weaving-the-web.org>
 * @group registration
 */
class RegistrationControllerTest extends WebTestCase
{
    protected $router;

    public function requireSQLiteFixtures()
    {
        return true;
    }

    public function setUp()
    {
        $this->client = $this->getClient();
        $this->router = $this->getService('router');
    }

    /**
     * @group security
     * @group isolated-testing
     */
    public function testLandAction()
    {
        $url = $this->router->generate('wtw_registration_land');
        $crawler = $this->client->request('GET', $url);
        $this->assertResponseStatusCodeEquals(200);

        $forms = $crawler->filter('form');

        /**
         * It should display
         * the registration form
         * the login form
         */
        $this->assertCount(
            2,
            $forms,
            'It should display the login form and the registration'
        );

        $registrationForm = $crawler->selectButton('Register')->form();
        $uri = $registrationForm->getUri();

        $crawler = $this->client->request('POST', $uri, []);
        $this->assertResponseStatusCodeEquals(200);

        $errors = $crawler->filter('.control-label');
        $this->assertCount(3, $errors);

        $registrationForm = $crawler->selectButton('Register')->form();
        $email = 'qa@weaving-the-web.org';
        $registrationForm->setValues([
            'fos_user_registration_form[username]' => 'Gordon',
            'fos_user_registration_form[email]' => $email,
            'fos_user_registration_form[plainPassword][first]' => '57^jHzco0dntGk15FsEn^PfuaQP@84j',
            'fos_user_registration_form[plainPassword][second]' => '57^jHzco0dntGk15FsEn^PfuaQP@84j'
        ]);
        $parameters = $registrationForm->getPhpValues();

        $this->client->request('POST', $uri, $parameters);
        $response = $this->client->getResponse();
        $this->assertEquals(302, $response->getStatusCode());

        /**
         * @var $userManager \WeavingTheWeb\Bundle\UserBundle\Doctrine\UserManager
         */
        $userManager = $this->getService('weaving_the_web_user.user_manager');
        $gordon = $userManager->findUserByUsername('Gordon');

        $this->assertInstanceOf('\WTW\UserBundle\Entity\User', $gordon);
        $this->assertEquals($email, $gordon->getEmail());

        $this->assertFalse($gordon->isEnabled());

        $this->assertTrue($gordon->hasRole($gordon::ROLE_DEFAULT));
        $this->assertFalse($gordon->hasRole($gordon::ROLE_SUPER_ADMIN));
    }
}
