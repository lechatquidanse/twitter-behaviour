<?php

/**
 * This file is part of the TwitterAnalytics package.
 *
 * (c) lechatquidanse
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LCQD\TwitterAnalytics\Handler;

use LCQD\TwitterAnalytics\Client\TwitterClient;
use LCQD\TwitterAnalytics\Common\SearchTweetsApi;

/**
 * TweetHandler
 *
 * Handler that can analyze twitter api response from http call
 *
 * @package TwitterAnalytics
 * @author lechatquidanse
 */
class TweetHandler
{
    /**
     * Trait with search tweets api utils
     */
    use SearchTweetsApi;

    /**
     * API_RESPONSE_TWEETS_KEY
     *
     * Key that identify array of tweets from twitter API response
     */
    const API_RESPONSE_TWEETS_KEY = 'statuses';

    /**
     * API_RESPONSE_METADATA_KEY
     *
     * Key that identify array of metadata from twitter API response
     */
    const API_RESPONSE_METADATA_KEY = 'search_metadata';

    /**
     * API_RESPONSE_TWEET_TEXT_KEY
     *
     * Key that identify text from a tweet array
     */
    const API_RESPONSE_TWEET_TEXT_KEY = 'text';

    /**
     * Api Response
     *
     * @var array
     */
    protected $apiResponse = array();

    /**
     * Client
     *
     * @var LCQD\TwitterAnalytics\Client\TwitterClient
     */
    protected $client;

    /**
     * Send Request
     *
     * Send a request from url and options preconfigured in class
     * Set response with response client
     *
     * @throws \Exception if client is not instance of TwitterClient
     * @throws \Exception if response of client is not an array
     * @return void
     */
    public function sendRequest()
    {
        $url = $this->getStUrl();
        $options = $this->generateStOptions();
        
        if (!($this->client instanceof TwitterClient)) {
            throw new \Exception("Client is not instance of TwitterClient", 1);
        }

        $response = $this->client->get($url, $options)->json();

        if (!is_array($response)) {
            throw new \Exception("Api Reponse from Send Request is not an array", 1);
        }

        $this->apiResponse = $response;
    }

    /**
     * Get Key Words From Tweeet
     *
     * Return all words that composed a text of a tweet
     *
     * @param array $tweet informations about a tweet (text, medtadata..)
     * @return array
     */
    public function getKeyWordsFromTweet(array $tweet)
    {
        $text = $this->getValueFromArrayKey(self::API_RESPONSE_TWEET_TEXT_KEY, $tweet) . "\n\n";

        return preg_split("/[\s,]+/", $text, -1, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * Send Request Words Repeater From
     *
     * Send a request with sendRequest method, after having created options of request
     *
     * @param string $from
     * @param integer $nbTweet
     * @return void
     */
    private function sendRequestWordsRepeaterFrom($from, $nbTweet = 100)
    {
        $this->addStQueryFrom($from)
            ->addStParameterCount($nbTweet)
            ->sendRequest();
    }

    /**
     * Count Words Repeater In Twwets
     *
     * @param array $tweets
     * @param boolean $sort, sort wordsCount by value if true, else nothing
     * @return array
     */
    public function countWordsRepeaterInTweets(array $tweets, $sort = true)
    {
        $wordsCount = array();

        foreach ($tweets as $tweet) {
            try {
                $keywords = $this->getKeyWordsFromTweet($tweet);

                foreach ($keywords as $keyword) {
                    if (!isset($wordsCount[$keyword])) {
                        $wordsCount[$keyword] = 1;
                    } else {
                        $wordsCount[$keyword]++;
                    }
                }
            } catch (\Exception $e) {
                //@todo: log
            }
        }

        if ($sort) {
            arsort($wordsCount);
        }

        return $wordsCount;
    }

    /**
     * Words Repeater From
     *
     * Returns an array filled with words and number of use foreach word, send by from on its last nbTweet
     *
     * @param string $from
     * @param integer $nbTweet
     * @throws \Exception if nbTweet To analyze > 100
     * @return array
     */
    public function wordsRepeaterFrom($from, $nbTweet = 100)
    {
        if ($nbTweet > 100) {
            throw new \Exception("Twitter API allow max count to 100 for search tweet api.\nYou have to add feature with next_results in metaData API call", 1);
        }

        $this->sendRequestWordsRepeaterFrom($from, $nbTweet);
        $tweets = $this->getTweetsFromApiResponse();

        return $this->countWordsRepeaterInTweets($tweets);
    }

    /**
     * Get Tweets From Api Response
     *
     * @return array
     */
    public function getTweetsFromApiResponse()
    {
        return $this->getValueFromArrayKey(self::API_RESPONSE_TWEETS_KEY, $this->apiResponse);
    }

    /**
     * Get MetaData From Api Response
     *
     * @return array
     */
    public function getMetaDataFromApiResponse()
    {
        return $this->getValueFromArrayKey(self::API_RESPONSE_METADATA_KEY, $this->apiResponse);
    }

    /**
     * Get Value From Array Key
     *
     * Return value from an array by its key
     *
     * @param string $key
     * @param array $array
     * @throws \Exception If key doesn't in array
     * @return mixed
     */
    private function getValueFromArrayKey($key, array $array)
    {
        if (!isset($array[$key])) {
            throw new \Exception(sprintf("Error, Twitter API is not well formated, missing key: %s", $key), 1);
        }

        return $array[$key];
    }

    /**
     * Get ApiResponse
     *
     * @return array
     */
    public function getApiResponse()
    {
        return $this->apiResponse;
    }

    /**
     * Set ApiResponse
     *
     * @param array $apiResponse
     * @return TweetHandler
     */
    public function setApiResponse(array $apiResponse)
    {
        $this->apiResponse = $apiResponse;

        return $this;
    }

    /**
     * Get Client
     *
     * @return TwitterClient
     */
    public function getClient()
    {
        return $client;
    }

    /**
     * Set Client
     *
     * @param TwitterClient $client
     * @return TweetHandler
     */
    public function setClient(TwitterClient $client)
    {
        $this->client = $client;

        return  $this;
    }
}
