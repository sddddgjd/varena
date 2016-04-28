<?php

class Db {

  static function init() {
    $dsn = Config::get('testing.enabled')
         ? Config::get('testing.database')
         : Config::get('general.database');

    $parts = self::parseDsn($dsn);
    ORM::configure(sprintf("mysql:host=%s;dbname=%s", $parts['host'], $parts['database']));
    ORM::configure('username', $parts['user']);
    ORM::configure('password', $parts['password']);
    // If you enable query logging, you can then run var_dump(ORM::get_query_log()) and var_dump(ORM::get_last_query())
    ORM::configure('logging', true);
    ORM::configure('driver_options', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
  }

  /**
   * Returns an array mapping user, password, host and database to their respective values.
   **/
  static function parseDsn($dsn) {
    $matches = array();
    $numMatches = preg_match('/^([^:]+):\/\/([^:@]+)(:([^@]+))?@([^\/]+)\/(.+)$/', $dsn, $matches);
    return array('driver' => $matches[1],
                 'user' => $matches[2],
                 'password' => $matches[4],
                 'host' => $matches[5],
                 'database' => $matches[6]);
  }

  static function tableExists($tableName) {
    $r = ORM::for_table($tableName)->raw_query("show tables like '$tableName'", null)->find_one();
    return ($r !== false);
  }

  /** Returns a DB result set that you can iterate with foreach ($result as $row) **/
  static function execute($query, $fetchStyle = PDO::FETCH_BOTH) {
    return ORM::get_db()->query($query, $fetchStyle);
  }

  static function executeSqlFile($fileName) {
    $dsn = Config::get('general.database');
    $parts = Db::parseDsn($dsn);
    $command = sprintf("cat {$fileName} | mysql -u %s -h %s %s",
                       $parts['user'],
                       $parts['host'],
                       $parts['database']);

    $exitCode = 0;
    $output = null;
    Log::info("Executing $command");
    exec($command, $output, $exitCode);
    if ($exitCode) {
      Log::critical('Output: ' . implode("\n", $output));
      die("Failed command: $command (code $exitCode)\n");
    }

  }

}

?>
