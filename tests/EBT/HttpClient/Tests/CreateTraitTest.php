<?php

/*
 * This file is a part of the Dimensions library.
 *
 * (c) 2014 Ebidtech
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EBT\HttpClient\Tests;

use EBT\HttpClient\CreateTrait;
use ESO\IReflection\ReflClass;

/**
 * CreateTraitTest
 *
 * @group clients
 */
class CreateTraitTest extends TestCase
{
    /**
     * @var CreateTrait
     */
    private $create;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->create = new CreateTraitObj();
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        $this->create = null;

        parent::tearDown();
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Expected host
     */
    public function testCreateInvalidHost()
    {
        $this->create->create(10, 't');
    }

    public function testCreate()
    {

        $host = 'http://eb.dev';
        $userAgent = 'User agent test';

        $client = $this->create->create($host, $userAgent);

        $this->assertEquals($host, $client->getBaseUrl());

        $this->assertEquals($userAgent, ReflClass::create($client)->getAnyPropertyValue('userAgent'));

        $listeners = $client->getEventDispatcher()->getListeners();
        $this->assertArrayHasKey('request.error', $listeners);
    }

    public function testCreateConfig()
    {
        $config = array(
            'curl.options' => array(
                'CURLOPT_DNS_USE_GLOBAL_CACHE' => false,
                'CURLOPT_VERBOSE' => false,
                'CURLOPT_FRESH_CONNECT' => true,
                'CURLOPT_FORBID_REUSE' => true,
                'CURLOPT_DNS_CACHE_TIMEOUT' => 5,
                'CURLOPT_CONNECTTIMEOUT_MS' => 1000,
                'CURLOPT_TIMEOUT_MS' => 2000
            )
        );

        $client = $this->create->create('http://eb.dev', 'user agent test', $config);

        $clientConfigCurlOptions = $client->getConfig()->get('curl.options');
        $this->assertInternalType('array', $clientConfigCurlOptions);
        $this->assertEquals(
            $config['curl.options'],
            array_intersect($config['curl.options'], $clientConfigCurlOptions)
        );
    }

    public function testCreateNoHost()
    {
        $client = $this->create->createNoHost('user agent test');
        $this->assertEmpty($client->getBaseUrl());
    }
}
