<?php

require_once __DIR__ . '/../lib/Util.php';

// Make sure we are in testing mode.
Config::get('testing.enabled')
  or die("Please set enabled = true in the [testing] section.\n");

// Drop and recreate the testing DB.
// Execute this at PDO level, since idiorm cannot connect to a non-existing DB.
$gdsn = DB::parseDsn(Config::get('general.database'));
$tdsn = DB::parseDsn(Config::get('testing.database'));

$pdo = new PDO('mysql:host=' . $tdsn['host'], $tdsn['user'], $tdsn['password']);
$pdo->query('drop database if exists ' . $tdsn['database']);
$pdo->query('create database if not exists ' . $tdsn['database']);

// Warning about passwords on command line.
if ($gdsn['password'] || $tdsn['password']) {
  print "This script needs to run some mysqldump and mysql shell commands.\n";
  print "However, your DB DSN includes a password. We cannot add plaintext passwords\n";
  print "to MySQL commands. Please specify your username/password in ~/.my.cnf like so:\n";
  print "\n";
  print "[client]\n";
  print "user=your_username\n";
  print "password=your_password\n";
}

// Copy the schema from the regular DB.
exec(sprintf('mysqldump -h %s -u %s %s -d | mysql -h %s -u %s %s',
             $gdsn['host'], $gdsn['user'], $gdsn['database'],
             $tdsn['host'], $tdsn['user'], $tdsn['database']));

// create some data
$userJohn = Model::factory('User')->create();
$userJohn->email = 'john@x.com';
$userJohn->username = 'john';
$userJohn->name = 'John Smith';
$userJohn->password = password_hash('1234', PASSWORD_DEFAULT);
$userJohn->save();
