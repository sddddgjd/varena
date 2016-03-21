<?php

/* Handles API calls. */

require_once '../lib/Util.php';

if (Request::get('password') != Config::get('general.apiPassword')) {
  terminate(Http::HTTP_FORBIDDEN);
}

$resource = Request::get('resource');

// Translate the request data into a file to print
switch ($resource) {
  case 'testInput':
  case 'testWitness':
    $problemId = Request::get('problemId');
    $test = Request::get('test');
    $p = Problem::get_by_id($problemId) or terminate(Http::HTTP_NOT_FOUND);
    $a = ($resource == 'testInput')
       ? $p->getTestInput($test)
       : $p->getTestWitness($test);
    if (!$a) {
      terminate(Http::HTTP_NOT_FOUND);
    }
    dumpFile($a->getFullPath());
    break;
    
  case 'grader':
    $problemId = Request::get('problemId');
    $p = Problem::get_by_id($problemId) or terminate(Http::HTTP_NOT_FOUND);
    $a = $p->getGrader() or terminate(Http::HTTP_NOT_FOUND);
    dumpFile($a->getFullPath());
    break;
    
  case 'source':
    $sourceId = Request::get('sourceId');
    $s = Source::get_by_id($sourceId) or terminate(Http::HTTP_NOT_FOUND);
    print $s->sourceCode;
    break;
    
  default:
    terminate(Http::HTTP_FORBIDDEN);
}

/**************************************************************************/

function terminate($statusCode) {
  Http::statusCode($statusCode);
  exit;
}

function dumpFile($file) {
  file_exists($file) or terminate(Http::HTTP_NOT_FOUND);
  readfile($file);
}

?>
