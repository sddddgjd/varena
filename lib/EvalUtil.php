<?php

/**
 * Evaluator-related utilities.
 **/
class EvalUtil {
  private $source;
  private $problem;
  private $user;
  private $dataDir;
  private $api;
  private $password;
  private $graderExtension;

  function __construct($source) {
    $this->source = $source;
    $this->problem = $source->getProblem();
    $this->user = $source->getUser();
    $this->dataDir = sprintf("%s/%s", Config::get('eval.cacheDir'),
                             $this->problem->name);
    $this->api = Config::get('eval.api');
    $this->password = Config::get('general.apiPassword');
    $this->graderExtension = mb_strtolower(
      pathinfo($this->problem->grader, PATHINFO_EXTENSION));

    printf("Evaluating source %d submitted by [%s] for problem [%s]\n",
           $this->source->id, $this->user->name, $this->problem->name);
  }

  function updateStatus($s) {
    $this->source->status = $s;
    $this->source->save();
  }

  function getInputFileName($test) {
    return "{$this->dataDir}/{$test}.in";
  }

  function getInputUrl($test) {
    return sprintf("%s?resource=%s&problemId=%s&test=%s",
                   $this->api, 'testInput', $this->problem->id, $test);
  }

  function getWitnessFileName($test) {
    return "{$this->dataDir}/{$test}.ok";
  }

  function getWitnessUrl($test) {
    return sprintf("%s?resource=%s&problemId=%s&test=%s",
                   $this->api, 'testWitness', $this->problem->id, $test);
  }

  function getGraderFileName() {
    return "{$this->dataDir}/grader.{$this->graderExtension}";
  }

  function getGraderUrl() {
    return sprintf("%s?resource=%s&problemId=%s",
                   $this->api, 'grader', $this->problem->id);
  }


  function getSourceFileName() {
    return "{$this->dataDir}/sources/{$this->source->id}.{$this->source->extension}";
  }

  function getSourceUrl() {
    return sprintf("%s?resource=%s&sourceId=%s",
                   $this->api, 'source', $this->source->id);
  }


  function fetchDataFile($file, $url) {
    @mkdir(dirname($file), 0777, true);

    $lastModified = file_exists($file)
                  ? filemtime($file)
                  : 0;

    $url .= "&password={$this->password}" .
         "&lastModified={$lastModified}";
  
    list ($contents, $httpCode) = Http::fetchUrl($url);
    printf("* Fetching file %s ==> return code = %d (%s)\n",
           $file, $httpCode, Http::STATUS_NAMES[$httpCode]);
    if ($httpCode == 200) {
      file_put_contents($file, $contents);
    }
  }

  /* Fetches input/witness files, grader and source file as needed. */
  function fetchAllData() {
    for ($i = 1; $i <= $this->problem->numTests; $i++) {
      // input file
      $file = $this->getInputFileName($i);
      $url = $this->getInputUrl($i);
      $this->fetchDataFile($file, $url);

      // witness file
      if ($this->problem->hasWitness) {
        $file = $this->getWitnessFileName($i);
        $url = $this->getWitnessUrl($i);
        $this->fetchDataFile($file, $url);
      }
    }

    // grader file
    if ($this->problem->grader) {
      $file = $this->getGraderFileName();
      $url = $this->getGraderUrl();
      $this->fetchDataFile($file, $url);
    }

    // source file
    $file = $this->getSourceFileName();
    $url = $this->getSourceUrl();
    $this->fetchDataFile($file, $url);
  }
}
