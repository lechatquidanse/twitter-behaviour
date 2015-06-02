<?php

namespace LCQD\TwitterAnalytics\Tests\Handler;

use LCQD\TwitterAnalytics\Handler\TweetHandler;

class TweetHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected function createTweetHandler()
    {
        return new TweetHandler();
    }
    
    public function testGetKeyWordsFromTweet()
    {
        $tweet = array('text' => 'Un essai d\'un test getKeyWordsFromTweet @phpunit');
        $keyWordsExpecteds = array('Un', 'essai', 'd\'un', 'test', 'getKeyWordsFromTweet', '@phpunit');

        $tweetHandler = $this->createTweetHandler();
        $keyWordsFromTweet = $tweetHandler->getKeyWordsFromTweet($tweet);

        $this->assertEquals($keyWordsExpecteds, $keyWordsFromTweet);
    }

    public function testCountWordsRepeaterInTweets()
    {
        $tweets = array(
                0 => array('text' => 'Un tweet de @lcqd #twitter'),
                1 => array('text' => 'Second tweet de @lcqd tweet')
                );

        $countWordsRepeaterExpected = array(
            'tweet' => 3,
            'de' => 2,
            '@lcqd' => 2,
            'Un' => 1,
            '#twitter' => 1,
            'Second' => 1
            );

        $tweetHandler = $this->createTweetHandler();
        $countWordsRepeaterFromTweets = $tweetHandler->countWordsRepeaterInTweets($tweets);

        $this->assertEquals($countWordsRepeaterExpected, $countWordsRepeaterFromTweets);
    }
}
