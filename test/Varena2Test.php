<?php

require_once __DIR__ . '/../lib/Util.php';

define('SELENIUM_URL', 'http://localhost:4444/wd/hub');
define('SELENIUM_PORT', 4444);
define('SELENIUM_DOWNLOAD_URL', 'http://selenium-release.storage.googleapis.com/index.html');

class GitHubTest extends PHPUnit_Framework_TestCase {

  protected $webDriver;
  protected $url;

  public static function setUpBeforeClass() {
    // Make sure we are in testing mode
    Config::get('testing.enabled')
      or die("Please set enabled = true in the [testing] section.\n");

    // Make sure someone is answering on the selenium port
    $sock = @fsockopen('localhost', SELENIUM_PORT)
          or die(sprintf("Please download the selenium standalone server from [%s] " .
                         "and run it with [java -jar /path/to/selenium.jar]\n",
                         SELENIUM_DOWNLOAD_URL));
    fclose($sock);
    
    // wipe the testing database
    Db::wipeTestingDatabase();

    // create some data
    $userJohn = Model::factory('User')->create();
    $userJohn->email = 'john@x.com';
    $userJohn->username = 'john';
    $userJohn->name = 'John Smith';
    $userJohn->password = password_hash('1234', PASSWORD_DEFAULT);
    $userJohn->save();
  }

  public function setUp() {
    $capabilities = [ \WebDriverCapabilityType::BROWSER_NAME => 'firefox' ];
    $this->webDriver = RemoteWebDriver::create(SELENIUM_URL, $capabilities);
    $this->url = Config::get('testing.url');
  }

  public function testGitHubHome() {
    $this->webDriver->get($this->url);
    $this->assertContains('Varena2', $this->webDriver->getTitle());
  }    

  public function tearDown() {
    $this->webDriver->quit();
  }
}
