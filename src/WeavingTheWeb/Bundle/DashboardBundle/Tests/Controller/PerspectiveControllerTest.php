<?php

namespace WeavingTheWeb\Bundle\DashboardBundle\Tests\Controller;

use WeavingTheWeb\Bundle\FrameworkExtraBundle\Test\WebTestCase;

/**
 * @group perspective
 */
class PerspectiveControllerTest extends WebTestCase
{
    public function setUp()
    {
        $this->client = $this->getClient();
    }

    /**
     * @test
     */
    public function it_should_show_perspectives()
    {
        /**
         * @var \Symfony\Component\Routing\Router $router
         */
        $router = $this->get('router');
        $showPerspectiveUrl = $router->generate('weaving_the_web_dashboard_show_perspective');

        $this->client->request('GET', $showPerspectiveUrl);
        $this->assertResponseStatusCodeEquals(200);
    }

}