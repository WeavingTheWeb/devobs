<?php

namespace WeavingTheWeb\Bundle\UserBundle\Tests\Controller;

use WeavingTheWeb\Bundle\FrameworkExtraBundle\Test\WebTestCase as TestCase;

class WebTestCase extends TestCase
{
    protected static $options = [];

    /**
     * @var \Symfony\Component\HttpKernel\KernelInterface
     */
    protected static $kernel;

    /**
     * @param $name
     * @param $value
     */
    public static function setOption($name, $value)
    {
        static::$options[$name] = $value;
    }

    public static function setUpBeforeClass()
    {
        if ((count(static::$options) > 0) && isset(static::$options['environment'])) {
            $options['environment'] = static::$options['environment'];
        } else {
            $options['environment'] = 'test';
        }

        if (null !== static::$kernel) {
            static::$kernel->shutdown();
        }

        self::$kernel = self::createKernel($options);
        self::$kernel->boot();
        self::$container = self::$kernel->getContainer();
    }

    /**
     * @param  array  $options
     * @param  array  $server
     * @return \Symfony\Component\BrowserKit\Client
     */
    public function setupAuthenticatedClient(array $options = array(), $server = array())
    {
        $this->client = $this->getAuthenticatedClient($options, $server);

        return $this->client;
    }
}
