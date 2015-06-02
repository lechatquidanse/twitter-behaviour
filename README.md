# twitter-behaviour

This repository provides a  a console command tool that accepts twitter account name (for instance Secretsales) and outputs keyword frequency for the past 100 tweets from a predefined account, most frequent on top, in the following format:

    keyword1, count
    keyword2, count
    ...


1) Installing
----------------------------------
### Clone the repository

    git clone git@github.com:lechatquidanse/twitter-behaviour.git
    

### Use Composer to install vendors

Vendors that will be installed are [phpunit][1] and [command console][2] from Symfony.

If you don't have Composer yet, download it following the instructions on
http://getcomposer.org/ or just run the following command:

    curl -s http://getcomposer.org/installer | php

Then, use the `composer` command to install required vendors:

    php composer.phar install

2) Configuration
--------------------------------

You have to add all the twitter key needed for OAuth configuration with this command

    mv src/LCQD/TwitterAnalytics/Resources/config/config.yml-dist src/LCQD/TwitterAnalytics/Resources/config/config.yml

Then add you parameters key in the new generetaed file.

3) Launching Application
--------------------------------

Congratulations! You're now ready to use the API:

    php bin/console lcqd:twitter:repeater [account_name]

With account_name is the account you want to analyze.

4) Testing Application
--------------------------------

You can launch UnitTests with this command:

    bin/phpunit

Enjoy!

[1]: https://phpunit.de/
[2]: https://github.com/symfony/Console

Authors
-------

* Stéphane EL MANOUNI
