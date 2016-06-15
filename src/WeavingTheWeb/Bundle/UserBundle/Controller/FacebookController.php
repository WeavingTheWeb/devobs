<?php

namespace WeavingTheWeb\Bundle\UserBundle\Controller;

use Facebook\FacebookRedirectLoginHelper,
    Facebook\FacebookSession,
    Facebook\FacebookRequestException;

use Sensio\Bundle\FrameworkExtraBundle\Configuration as Extra;

use Symfony\Component\HttpFoundation\RedirectResponse,
    Symfony\Component\HttpFoundation\Request;

/**
 * @author  Thierry Marianne <thierry.marianne@weaving-the-web.org>
 * @Extra\Route(
 *      "/facebook",
 *      service="weaving_the_web_user.controller.facebook"
 * )
 */
class FacebookController
{
    public $appId;

    public $appSecret;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    public $logger;

    /**
     * @var \Symfony\Component\Routing\Router
     */
    public $router;

    /**
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    public $session;

    /**
     * @var \WeavingTheWeb\Bundle\UserBundle\Repository\AccessTokenRepository
     */
    public $accessTokenRepository;

    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    public $entityManager;

    /**
     * @Extra\Route("/login", name="weaving_the_web_user_facebook_login")
     */
    public function loginAction()
    {
        $this->session->start();

        $helper = $this->getFacebookRedirectLoginHelper();

        return new RedirectResponse($helper->getLoginUrl([
            'user_birthday',
            'user_religion_politics',
            'user_relationships',
            'user_relationship_details',
            'user_hometown',
            'user_location',
            'user_likes',
            'user_education_history',
            'user_work_history',
            'user_website',
            'user_groups',
            'user_managed_groups',
            'user_events',
            'user_photos',
            'user_videos',
            'user_friends',
            'user_about_me',
            'user_status',
            'user_posts',
            'read_stream',
            'read_mailbox',
            'email',
            'ads_management',
            'read_insights',
            'manage_notifications',
            'publish_actions',
            'read_custom_friendlists',
            'public_profile'
        ]));
    }

    /**
     * @Extra\Route("/get-access-token", name="weaving_the_web_user_facebook_get_access_token")
     * @Extra\Template("WeavingTheWebUserBundle:Facebook:get-access-token.html.twig")
     */
    public function getAccessTokenAction(Request $request)
    {
        if (!$request->query->get('code')) {
            return new RedirectResponse($this->router->generate('weaving_the_web_user_facebook_login'));
        }

        $helper = $this->getFacebookRedirectLoginHelper();

        try {
            $session = $helper->getSessionFromRedirect();
            $accessToken = $this->accessTokenRepository->make((string)$session->getAccessToken());
            $this->entityManager->persist($accessToken);
            $this->entityManager->flush();

            return [];
        } catch(\Exception $exception) {
            $this->logger->error($exception->getMessage());

            return ['error' => $exception->getMessage()];
        }
    }

    /**
     * @return string
     */
    protected function getRedirectUrl()
    {
        $router = $this->router;

        return $this->router->generate('weaving_the_web_user_facebook_get_access_token', [], $router::ABSOLUTE_URL);
    }

    /**
     * @return FacebookRedirectLoginHelper
     */
    protected function getFacebookRedirectLoginHelper()
    {
        FacebookSession::setDefaultApplication($this->appId, $this->appSecret);

        $helper = new FacebookRedirectLoginHelper($this->getRedirectUrl());
        $helper->disableSessionStatusCheck();

        return $helper;
    }
}