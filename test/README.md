Testing procedure:

One-time setup
==============

1. Go to this directory:

cd test

2. Download Composer:

curl -sS https://getcomposer.org/installer | php

3. Install dependencies for testing (as specified in composer.json):

php composer.phar install

4. Download the Selenium standalone server jar from

http://selenium-release.storage.googleapis.com/index.html


Running the test suite
======================

1. Put the site in testing mode (so that it uses the test DB):

* set [testing] enabled = true in varena2.conf

2. Run the Selenium server:

java /path/to/selenium.jar

3. Run the tests:

test/vendor/bin/phpunit test/Varena2Test.php

4. (Probably) Take the site out of testing mode and stop the Selenium server.
