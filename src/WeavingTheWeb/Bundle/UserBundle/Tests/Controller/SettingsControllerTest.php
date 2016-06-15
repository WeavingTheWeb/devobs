<?php

namespace WeavingTheWeb\Bundle\UserBundle\Tests\Controller;

use WeavingTheWeb\Bundle\UserBundle\Tests\DataFixtures\ORM\TokenData;

/**
 * @package WeavingTheWeb\Bundle\UserBundle\Tests\Controller
 * @author Thierry Marianne <thierry.marianne@weaving-the-web.org>
 * @group settings
 */
class SettingsControllerTest extends WebTestCase
{
    /**
     * @var \Symfony\Component\Routing\Router $router
     */
    protected $router;

    public function requireSQLiteFixtures()
    {
        return true;
    }

    public static function setUpBeforeClass()
    {
        self::setOption('environment', 'test');
        parent::setUpBeforeClass();
    }

    public function setUp()
    {
        /**
         * @var \Symfony\Component\BrowserKit\Client $client
         */
        $this->client = $this->setupAuthenticatedClient(['follow_redirects' => true]);
        $this->router = $this->getService('router');
    }

    /**
     * @group security
     * @group isolated-testing
     */
    public function testShowAction()
    {
        $router = $this->router;

        /**
         * @var \Symfony\Component\DomCrawler\Crawler $crawler
         */
        $crawler = $this->client->request('GET', $router->generate('weaving_the_web_user_show_settings'));
        $this->assertResponseStatusCodeEquals(200);

        $crawler->filter('h1');
        $this->assertCount(1, $crawler);

        /**
         * @var $translator \Symfony\Component\Translation\Translator
         */
        $translator = $this->getService('translator');
        $title = $translator->trans('title_settings', [], 'user');
        $this->assertContains($title, $crawler->text());

        $forms = $crawler->filter('form');
        $this->assertCount(1, $forms);

        $form = $crawler->selectButton('Save')->form();
        $this->assertEquals('POST', $form->getMethod());

        $saveSettingsUrl = $router->generate('weaving_the_web_user_save_settings', [], $router::ABSOLUTE_URL);
        $this->assertEquals($saveSettingsUrl, $form->getUri());

        $form->setValues([
            'user[email]' => 'tweets@weaving-the-web.org',
            'user[username]' => $this->getParameter('api_wtw_repositories_user_name'),
            'user[currentPassword]' => '',
            'user[twitter_username]' => 'w34v1ng'
        ]);

        $fieldValues = $form->getPhpValues();
        $crawler = $this->client->request('POST', $saveSettingsUrl, $fieldValues);
        $this->assertResponseStatusCodeEquals(200);

        $currentPasswordError = $translator->trans('field_error_current_password', [], 'user');
        $this->assertContains($currentPasswordError, $crawler->text());
    }

    /**
     * @dataProvider getConnectionCases
     *
     * @param $mockingContext
     * @param $expectedAlerts
     * @param $expectedStatusCode
     * @param $expectedMessage
     * @param null $callbacks
     * @param bool $preLoadTokenFixtures
     */
    public function testConnectToTwitterAction(
        $mockingContext,
        $expectedAlerts,
        $expectedStatusCode,
        $expectedMessage,
        $callbacks = null,
        $preLoadTokenFixtures = false
    )
    {
        $this->client->followRedirects(false);
        /**
         * @var $translator \Symfony\Component\Translation\Translator
         */
        $translator = $this->getService('translator');


        if ($preLoadTokenFixtures) {
            // Ensures two same token could not be saved twice
            $this->loader->loadFromDirectory(__DIR__ . '/../DataFixtures');
            $fixtures = $this->loader->getFixtures();
            foreach ($fixtures as $fixture) {
                if ($fixture instanceof TokenData) {
                    $this->executor->execute([$fixture], true);
                }
            }

            $this->assertTokenHasBeenSaved();
        }

        $connectToTwitterUrl = $this->router->generate('weaving_the_web_user_connect_to_twitter');

        $twitterOAuthMock = call_user_func_array([$this, 'getTwitterMock'], $mockingContext['twitter_oauth_mock_arguments']);
        $this->client->getContainer()->set('fos_twitter.api', $twitterOAuthMock);

        $this->client->request('GET', $connectToTwitterUrl);

        $redirectResponse = $this->assertResponseStatusCodeEquals(302);

        $this->assertCount(1, $redirectResponse->headers->getCookies());

        if (!is_array($mockingContext['expected_redirect_location'])
            || !array_key_exists('name', $mockingContext['expected_redirect_location'])) {
            $this->fail('Invalid mocking context');
        } else {
            $expectedRedirectLocation = $this->router->generate($mockingContext['expected_redirect_location']['name']);
            $this->assertEquals($expectedRedirectLocation, $redirectResponse->headers->get('location'));

            $crawler = $this->client->followRedirect();

            $this->assertResponseStatusCodeEquals($expectedStatusCode);

            if ($expectedStatusCode === 302) {
                $crawler = $this->client->followRedirect();
            }

            $content = $this->client->getResponse()->getContent();

            foreach ($expectedAlerts as $alertType => $expectedCount) {
                $this->assertCount($expectedCount, $crawler->filter('.alert-' . $alertType), $content);

                if ($expectedCount > 0) {
                    $text = $crawler->filter('.alert-' . $alertType)->text();
                    $message = $translator->trans($expectedMessage, [], 'user');
                    $this->assertEquals($message, trim($text));
                    $this->assertNotEquals($expectedMessage, $text);
                }
            }

            if (!is_null($callbacks)) {
                foreach ($callbacks as $callback) {
                   $this->$callback();
                }
            }
        }
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getConnectionCases()
    {
        $dummyRequestToken = ['oauth_token' => 'token_value', 'oauth_token_secret' => 'oauth_token_secret'];
        $dummyAuthorizeUrl = ['name' => 'weaving_the_web_user_go_to_settings'];

        return [
            [
                'mocking_context' => [
                    'twitter_oauth_mock_arguments' => [],
                    'expected_redirect_location' => ['name' => 'weaving_the_web_user_show_settings'],
                ],
                'expected_alerts' => ['error' => 1, 'info' => 0],
                'expected_status_code' => 200,
                'expected_message' => 'failure_during_oauth_validation',
            ], [
                'mocking_context' => [
                    'twitter_oauth_mock_arguments' => [$dummyRequestToken, $dummyAuthorizeUrl],
                    'expected_redirect_location' => $dummyAuthorizeUrl,
                ],
                'expected_alerts' => ['error' => 0, 'info' => 1],
                'expected_status_code' => 302,
                'expected_message' => 'successful_access_token_persistence',
                'callbacks' => [
                    'assertTokenHasBeenSaved',
                    'assertUserHasBeenUpdated',
                ]
            ], [
                'mocking_context' => [
                    'twitter_oauth_mock_arguments' => [$dummyRequestToken, $dummyAuthorizeUrl],
                    'expected_redirect_location' => $dummyAuthorizeUrl,
                ],
                'expected_alerts' => ['error' => 0, 'info' => 1],
                'expected_status_code' => 302,
                'expected_message' => 'existing_access_token',
                'callbacks' => [
                    'assertTokenHasBeenSaved',
                    'assertUserHasBeenUpdated',
                ],
                'load_token_fixtures' => true,
            ]
        ];
    }

    /**
     * Excludes token data fixtures
     *
     * @return mixed
     */
    public function getFixturesDirectories()
    {
        $finder = parent::getFixturesDirectories();

        return $finder->notPath('/Tests/');
    }

    public function assertTokenHasBeenSaved()
    {
        /**
         * @var \WeavingTheWeb\Bundle\ApiBundle\Repository\TokenRepository $tokenRepository
         */
        $tokenRepository = $this->getService('weaving_the_web_api.repository.token');
        $accessToken = $this->getParameter('weaving_the_web_user.test.access_token');

        $token = $tokenRepository->findBy(['oauthToken' => $accessToken['arguments']['oauth_token']]);
        $this->assertCount(1, $token);
    }

    public function assertUserHasBeenUpdated()
    {
        /**
         * @var \Doctrine\ORM\EntityRepository
         */
        $userRepository = $this->getService('weaving_the_web_api.repository.user');
        $accessToken = $this->getParameter('weaving_the_web_user.test.access_token');

        $user = $userRepository->findBy([
            'twitterID' => $accessToken['arguments']['user_id']
        ]);
        $this->assertCount(1, $user);
    }

    /**
     * @param array $requestToken
     * @param null $route
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getTwitterMock(array $requestToken = [], $route = null)
    {
        $twitterOAuthMockBuilder = $this->getMockBuilder('\WeavingTheWeb\UserBundle\Tests\TwitterOauth')
            ->setMethods(['getRequestToken', 'getAuthorizeUrl', 'setOauthToken', 'get'])
            ->disableOriginalConstructor();

        $twitterOauthMock = $twitterOAuthMockBuilder->getMock();
        $twitterOauthMock->expects($this->exactly(1))->method('getRequestToken')->will(
            $this->returnValue($requestToken)
        );

        $twitterOauthMock->expects($this->any())->method('setOauthToken')->will(
            $this->returnValue(null)
        );


        $twitterOauthMock->expects($this->any())->method('get')->will(
            $this->returnValue((object)['name' => 'User', 'screen_name' => 'user_', 'id' => 1 ])
        );

        if (!is_null($route) && is_array($route) && array_key_exists('name', $route)) {
            $url = $this->router->generate($route['name']);
            $twitterOauthMock->expects($this->exactly(1))->method('getAuthorizeUrl')->will(
                $this->returnValue($url)
            );
            $twitterOauthMock->http_code = 200;
        }

        return $twitterOauthMock;
    }

    /**
     * @group security
     * @group isolated-testing
     */
    public function testSaveTokenAction()
    {
        $this->client->followRedirects(false);
        $saveTokenUrl = $this->router->generate('weaving_the_web_user_save_token');
        $showSettingsUrl = $this->router->generate('weaving_the_web_user_show_settings');

        $this->client->request('GET', $saveTokenUrl);
        $redirectResponse = $this->assertResponseStatusCodeEquals(302);
        $this->assertEquals($showSettingsUrl, $redirectResponse->headers->get('location'));

        /** @var \Symfony\Component\DomCrawler\Crawler $crawler */
        $crawler = $this->client->followRedirect();
        $this->assertResponseStatusCodeEquals(200);

        $linkLabel = $this->getService('translator')->trans('label_connect_to_twitter', [], 'user');
        $linkCrawler = $crawler->selectLink($linkLabel);
        $this->assertCount(1, $linkCrawler, 'It should render a link to connect with twitter.');

        $this->assertContains('Save', $this->client->getResponse()->getContent());

        $form = $crawler->selectButton('Save')->form();
        $crawler = $this->client->submit($form, [
            'user[username]' => 'adama',
            'user[email]' => 'test@weaving-the-web.org',
            'user[twitter_username]' => 'thierrymarianne',
            'user[currentPassword]' => $this->getParameter('api_wtw_repositories_password')
        ]);
        $this->assertResponseStatusCodeEquals(200);

        $linkCrawler = $crawler->selectLink($linkLabel);
        $this->assertCount(1, $linkCrawler, $this->client->getResponse()->getContent());
    }
}
