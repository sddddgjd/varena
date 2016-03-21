#!/usr/bin/php
<?php

/**
 * Checks whether the developer modified one of the files varena2.conf or
 * www/.htaccess. If they did, they should push the same changes to
 * varena2.conf.sample and www/.htaccess.sample respectively. Specifically,
 * we check whether
 * - there are new sections in varena2.conf;
 * - there are new variables in varena2.conf;
 * - some variables changed type in varena2.conf;
 * - there are new RewriteRules (commented or not) in www/.htaccess.
 */

// We should already be at the root of the client
if (($conf = parse_ini_file('varena2.conf', true)) === false) {
  error('Cannot read varena2.conf');
}
if (($confSample = parse_ini_file('varena2.conf.sample', true)) === false) {
  error('Cannot read varena2.conf');
}

foreach ($conf as $sectionTitle => $sectionVars) {
  // Check that no new sections are defined
  if (!array_key_exists($sectionTitle, $confSample)) {
    error("The section *** [$sectionTitle] *** is defined in varena2.conf, " .
          "but not in varena2.conf.sample. Please add it to varena2.conf.sample.");
  }

  foreach ($sectionVars as $key => $value) {
    // Check that no new variables are defined
    if (!array_key_exists($key, $confSample[$sectionTitle])) {
      error("The variable *** [$sectionTitle.$key] *** is defined in varena2.conf, " .
            "but not in varena2.conf.sample. Please add it to varena2.conf.sample.");
    }

    // Check that variable types haven't changed
    $typeConf = gettype($value);
    $typeConfSample = gettype($confSample[$sectionTitle][$key]);
    if ($typeConf != $typeConfSample) {
      error("The variable *** [$sectionTitle].$key *** has type '$typeConf' in " .
            "varena2.conf, but type '$typeConfSample' in varena2.conf.sample. " .
            "Please reconcile them.");
    }
  }
}

if (($htaccess = readRewriteRules('www/.htaccess')) === false) {
  error('Cannot read www/.htaccess');
}
if (($htaccessSample = readRewriteRules('www/.htaccess.sample')) === false) {
  error('Cannot read www/.htaccess.sample');
}

foreach ($htaccess as $rule) {
  if (!in_array($rule, $htaccessSample)) {
    error("The RewriteRule *** $rule *** is defined in www/.htaccess, " .
          "but not in www/.htaccess.sample. Please reconcile the files.");
  }
}

/***************************************************************************/

// Reads the file, retains only the lines containing RewriteRule statements
// and strips the comments
function readRewriteRules($filename) {
  if (($lines = file($filename)) === false) {
    return false;
  }

  $result = array();
  foreach ($lines as $line) {
    $matches = array();
    if (preg_match("/^(#+\s+)?RewriteRule\s+(.*)/", trim($line), $matches)) {
      $result[] = $matches[2];
    }
  }
  return $result;
}

function error($msg) {
  print "The pre-commit hook encountered an error.\n";
  print "If you know what you are doing, you can bypass this error by using the -n (--no-verify) flag:\n";
  print "\n";
  print "    git commit -n\n";
  print "\n";
  print "The error message was:\n";
  print $msg . "\n";
  exit(1);
}

?>
