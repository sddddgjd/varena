<?php

/* Outputs a problem attachment */

require_once '../lib/Util.php';

$problemName = Request::get('problemName');
$fileName = Request::get('fileName');

$p = Problem::get_by_name($problemName);
if ($p) {
  $a = Attachment::get_by_problemId_name($p->id, $fileName);
  if ($a) {
    $path = $a->getFullPath();
    if (file_exists($path)) {
      header('Content-Type: ' . mime_content_type($path));
      readfile($path);
    }
  }
}

?>
