<?php

namespace WeavingTheWeb\Bundle\ApiBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface,
    Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Inflector\Inflector;
use WeavingTheWeb\Bundle\ApiBundle\Entity\Status;

class UserStreamData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $status = 'This is a tweet text.';
        $properties = [
            'text' => $status,
            'api_document' => json_encode(['text' => $status]),
            'identifier' => 'access token',
            'indexed' => false,
            'name' => 'Thierry Marianne',
            'screen_name' => 'thierrymarianne',
            'user_avatar' => 'http://avatar.url',
            'status_id' => 194987972,
        ];

        $userStatus = $this->makeUserStatus($properties);
        $manager->persist($userStatus);

        $encodedUserStream = file_get_contents(__DIR__ . '/../../Tests/Resources/fixtures/user-stream.base64');
        $userStatusCollection = unserialize(base64_decode($encodedUserStream));

        foreach ($userStatusCollection as $userStatus) {
            $manager->persist($userStatus);
        }


        $manager->flush();
    }

    /**
     * @param array $properties
     *
     * @return Status
     */
    protected function makeUserStatus(array $properties)
    {
        $status = new Status();

        $status->setText($properties['text']);
        $status->setApiDocument($properties['api_document']);
        $status->setUserAvatar($properties['user_avatar']);
        $status->setName($properties['name']);
        $status->setScreenName($properties['screen_name']);
        $status->setIdentifier($properties['identifier']);
        $status->setIndexed($properties['indexed']);
        $status->setStatusId($properties['status_id']);
        $status->setCreatedAt(new \DateTime());
        $status->setUpdatedAt(new \DateTime());

        return $status;
    }
}
