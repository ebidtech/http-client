<?php

/*
 * This file is a part of the HTTP Client library.
 *
 * (c) 2014 Ebidtech
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EBT\HttpClient;

use Guzzle\Http\Client as GuzzleHttpClient;
use Guzzle\Common\Event as GuzzleEvent;
use Guzzle\Http\Message\Response as GuzzleResponse;
use Guzzle\Http\Message\Request as GuzzleRequest;
use Guzzle\Plugin\Backoff\BackoffPlugin;
use Guzzle\Plugin\Backoff\TruncatedBackoffStrategy;
use Guzzle\Plugin\Backoff\CurlBackoffStrategy;
use Guzzle\Plugin\Backoff\CallbackBackoffStrategy;
use EBT\HttpClient\Exception\InvalidArgumentException;
use EBT\HttpClient\Exception\RuntimeException;

/**
 * CreateTrait
 */
trait CreateTrait
{
    /**
     * Create HTTP client and add listener to catch errors.
     *
     * @param string $host                 Host, eg: http://eb.dev
     * @param string $userAgent            User agent used by the HTTP client.
     * @param array  $config               {@link http://guzzlephp.org/http-client/client.html#configuration-options}
     * @param bool   $retryOnCurlException If true will retry on curl exception
     *
     * @throws InvalidArgumentException
     *
     * @return GuzzleHttpClient
     */
    private function create($host, $userAgent, array $config = array(), $retryOnCurlException = false)
    {
        if (!is_string($host)) {
            throw new InvalidArgumentException(sprintf('Expected host as string got "%s"', gettype($host)));
        }

        // create client
        $client = new GuzzleHttpClient($host, $config);
        $client->setUserAgent((string) $userAgent);
        if ($retryOnCurlException) {
            $this->confRetryOnCurlException($client);
        }

        // listener completed an unsuccessful request
        $client->getEventDispatcher()->addListener(
            'request.error',
            function (GuzzleEvent $event) {
                $event->stopPropagation();
                /** @var $response GuzzleResponse */
                $response = $event['response'];
                throw new RuntimeException($response->getBody(), $response->getStatusCode());
            }
        );

        return $client;
    }

    /**
     * Configure a binary exponential backoff on curl exception, this uses the Backoff Guzzle plugin.
     *
     * @param GuzzleHttpClient $client
     */
    public function confRetryOnCurlException(GuzzleHttpClient $client)
    {
        $backoff = new BackoffPlugin(
            // Truncate the number of backoffs to 7
            new TruncatedBackoffStrategy(
                7,
                // Retry transient curl errors
                new CurlBackoffStrategy(
                    null,
                    // Use the custom retry delay method instead of default exponential backoff
                    new CallbackBackoffStrategy(__CLASS__ . '::calculateRetryDelay', false)
                )
            )
        );

        $client->addSubscriber($backoff);
    }

    /**
     * Calculate the amount of time needed for an exponential backoff to wait
     * before retrying a request
     *
     * @param int $retries Number of retries
     *
     * @return float Returns the amount of time to wait in seconds
     */
    public static function calculateRetryDelay($retries)
    {
        return $retries == 0 ? 0 : (500 * (int) pow(2, $retries - 1)) / 1000;
    }

    /**
     * Create HTTP client without "fixed" host (aka base URL) and add listener to catch errors.
     *
     * @param string $userAgent User agent used by the HTTP client.
     * @param array  $config    {@link http://guzzlephp.org/http-client/client.html#configuration-options}
     *
     * @return GuzzleHttpClient
     */
    private function createNoHost($userAgent, array $config = array())
    {
        return $this->create('', $userAgent, $config);
    }
}
