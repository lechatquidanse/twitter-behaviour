<?php

namespace LCQD\TwitterAnalytics\Command;

use LCQD\TwitterAnalytics\Client\TwitterClient;
use LCQD\TwitterAnalytics\Handler\TweetHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;

class WordCounterCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('lcqd:twitter:repeater')
            ->setDescription('Outputs keyword frequency for the past 100 tweets from a predefined account, most frequent on top,')
            ->addArgument(
                'account',
                InputArgument::OPTIONAL,
                'Wich account do you want to analyze (secretsales by default)?'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $configFile = __DIR__ . '/../Resources/config/config.yml';

        try {
            $yaml = new Parser();
            $parameters = $yaml->parse(@file_get_contents($configFile));

            if (!isset($parameters['twitter_api'])) {
                throw new \Exception(sprintf("Error Processing Request, missing twitter_api in parameters file %s", $configFile), 1);
            }

            $client = new TwitterClient($parameters['twitter_api']);
            $tweetHandler = new TweetHandler();
            $tweetHandler->setClient($client);

            $account = $input->getArgument('account') ? $input->getArgument('name') : 'secretsales';
            $wordsRepeater = $tweetHandler->wordsRepeaterFrom($account, 100);
            
            foreach ($wordsRepeater as $word => $count) {
                $output->writeln($word . ',' . $count);
            }
        } catch (ParseException $e) {
            $output->writeln(sprintf("Unable to parse the YAML string: %s", $e->getMessage()));
        } catch (\Exception $e) {
            $output->writeln(sprintf("Command Error request: %s", $e->getMessage()));
        }
    }
}
