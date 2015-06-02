<?php

/**
 * This file is part of the TwitterAnalytics package.
 *
 * (c) lechatquidanse
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LCQD\TwitterAnalytics\Client;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Message\ResponseInterface;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use Monolog\Logger;

/**
 * TwitterClient
 *
 * @package TwitterAnalytics
 * @author lechatquidanse
 */
class TwitterClient
{
    /**
     * Base Url
     *
     * @var string
     */
    protected $baseUrl = 'https://api.twitter.com/1.1/';

    /**
     * Client
     *
     * HTTP client that send HTTP requests
     *
     * @var GuzzleHttp\Client $client
     */
    protected $client;

    /**
     * Oauth
     *
     * @var GuzzleHttp\Subscriber\Oauth\Oauth1 $oauth
     */
    protected $oauth;

    /**
     * Oauth Configs
     *
     * @var array
     */
    protected $oauthConfigs = array();

    /**
     * Logger
     *
     * @var Monolog\Logger $logger
     */
    protected $logger;

    /**
     * __construct
     */
    public function __construct(array $oauthConfigs)
    {
        $this->initClient($oauthConfigs);
    }

    /**
     * Get
     *
     * Send a GET request
     *
     * @param string|array|Url $url     URL or URI template
     * @param array            $options Array of request options to apply.
     *
     * @return ResponseInterface
     * @throws RequestException When an error is encountered
     */
    public function get($url = null, $options = [])
    {
        return $this->client->get($url, $options);
    }

    /**
     * Init Oauth
     *
     * @param  array  $oauthConfigs
     * @return boolean true if oauthCongis array is not empty, else false
     */
    public function initOauth(array $oauthConfigs)
    {
        $this->setOauthConfigs($oauthConfigs);
        $this->oauth = new Oauth1($this->oauthConfigs);
    }

    /**
     * Init Client
     *
     * Set Client for Twitter Api With Oauth
     *
     * @return void
     */
    public function initClient(array $oauthConfigs = null)
    {
        $this->client = new Client([
            'base_url' => $this->baseUrl
        ]);

        if (is_array($oauthConfigs)) {
            $this->initOauth($oauthConfigs);
            $this->client->setDefaultOption('auth', 'oauth');
            $this->client->getEmitter()->attach($this->oauth);
        }
    }

    /**
     * Set Client
     *
     * @param ClientInterface $client
     * @return TweetHandler
     */
    public function setClient(ClientInterface $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get Client
     *
     * @return ClientInterface
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set Oauth
     *
     * @param Oauth1 $oauth
     * @return TweetHandler
     */
    public function setOauth(Oauth1 $oauth)
    {
        $this->oauth = $oauth;

        return $this;
    }

    /**
     * Get Oauth
     *
     * @return Oauth1
     */
    public function getOauth()
    {
        return $this->oauth;
    }

    /**
     * Set Oauth Configs
     *
     * @param array $oauthConfigs
     * @throws \Exception If missing a key in $oauthConfigs
     * @return TweetHandler
     */
    public function setOauthConfigs(array $oauthConfigs)
    {
        if (!isset($oauthConfigs['consumer_key']) || !isset($oauthConfigs['consumer_secret']) || !isset($oauthConfigs['token']) || !isset($oauthConfigs['token_secret'])) {
            throw new \Exception("Twitter CLient, missing Oauth Configs", 1);
        }

        $this->oauthConfigs = $oauthConfigs;

        return $this;
    }

    /**
     * Get Oauth
     *
     * @return array
     */
    public function getOauthConfigs()
    {
        return $this->oauthConfigs;
    }
}
