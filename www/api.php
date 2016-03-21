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
    break;
    
  case 'grader':
    $problemId = Request::get('problemId');
    $p = Problem::get_by_id($problemId) or terminate(Http::HTTP_NOT_FOUND);
    $a = $p->getGrader() or terminate(Http::HTTP_NOT_FOUND);
    break;
    
  default:
    terminate(Http::HTTP_FORBIDDEN);
}

$path = $a->getFullPath();
file_exists($path) or terminate(Http::HTTP_NOT_FOUND);

// Print the file if it's newer than $lastModified, otherwise return 304
$lastModified = Request::get('lastModified');
if (filemtime($path) <= $lastModified) {
  terminate(Http::HTTP_NOT_MODIFIED);
}

readfile($path);
exit;

/**************************************************************************/

function terminate($statusCode) {
  Http::statusCode($statusCode);
  exit;
}

?>
