<?php
// An example of using php-webdriver.

require_once __DIR__ . '/../lib/Util.php';

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;

spl_autoload_register(function($class) {
  echo "Autoloading [$class]\n";

  $prefix = 'Facebook\\WebDriver\\';
  assert(StringUtil::startsWith($class, $prefix));
  $class = substr($class, strlen($prefix));
  $class = str_replace('\\', '/', $class);

  require_once __DIR__
    . '/../lib/third-party/php-webdriver/lib/'
    . $class
    . '.php';
});

$host = 'http://localhost:4444/wd/hub'; // this is the default
$capabilities = DesiredCapabilities::firefox();
$driver = RemoteWebDriver::create($host, $capabilities);

// navigate to 'http://beta.varena.ro/'
$driver->get('http://beta.varena.ro/');

// click the link 'login'
$link = $driver->findElement(WebDriverBy::linkText(_('login')));
$link->click();

// print the title of the current page
echo "The title is '" . $driver->getTitle() . "'\n";

// close the browser
$driver->quit();
