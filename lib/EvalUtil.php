<?php

/**
 * Evaluator-related utilities.
 **/
class EvalUtil {
  private $source;
  private $problem;
  private $user;
  private $dataDir;
  private $workDir;
  private $api;
  private $password;
  private $graderExtension;

  const COMPILERS = [
    'c' => 'gcc -Wall -O2 -static -std=c11 %s -o %s -lm 2>&1',
    'cpp' => 'g++ -Wall -O2 -static -std=c++11 %s -o %s -lm 2>&1',
  ];
  const COMPILER_OUTPUT_LINES = 50;

  function __construct($source) {
    $this->source = $source;
    $this->problem = $source->getProblem();
    $this->user = $source->getUser();
    $this->dataDir = sprintf("%s/%s", Config::get('eval.cacheDir'),
                             $this->problem->name);
    $this->workDir = Config::get('eval.workDir');
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

  function getGraderBinary() {
    return "{$this->dataDir}/grader";
  }

  function getGraderUrl() {
    return sprintf("%s?resource=%s&problemId=%s",
                   $this->api, 'grader', $this->problem->id);
  }

  function getSourceFileName() {
    return "{$this->dataDir}/sources/{$this->source->id}.{$this->source->extension}";
  }

  function getSourceBinary() { // excuse the oxymoron
    return "{$this->dataDir}/sources/{$this->source->id}";
  }

  function getSourceUrl() {
    return sprintf("%s?resource=%s&sourceId=%s",
                   $this->api, 'source', $this->source->id);
  }

  function getWorkInputFile() {
    return "{$this->workDir}/{$this->problem->name}.in";
  }

  function getWorkOutputFile() {
    return "{$this->workDir}/{$this->problem->name}.out";
  }

  function getWorkWitnessFile() {
    return "{$this->workDir}/{$this->problem->name}.ok";
  }

  function getWorkBinary() {
    return "{$this->workDir}/binary";
  }

  function getWorkGraderBinary() {
    return "{$this->workDir}/grader";
  }

  /**
   * $obj is a database object whose 'created' field indicates whether
   * we need to refetch the file.
   **/
  function fetchDataFile($obj, $file, $url) {
    @mkdir(dirname($file), 0777, true);

    if (!file_exists($file) ||
        (filemtime($file) < $obj->created)) {
      $url .= "&password={$this->password}";
      list ($contents, $httpCode) = Http::fetchUrl($url);
      printf("* Fetching file %s ==> return code = %d (%s)\n",
        $file, $httpCode, Http::STATUS_NAMES[$httpCode]);
      if ($httpCode == 200) {
        file_put_contents($file, $contents);
      } else {
        throw new Exception(Source::STATUS_NO_TESTS);
      }
    }
  }

  /* Fetches input/witness files, grader and source file as needed. */
  function fetchAllData() {
    for ($i = 1; $i <= $this->problem->numTests; $i++) {
      // input file
      $a = $this->problem->getTestInput($i);
      $file = $this->getInputFileName($i);
      $url = $this->getInputUrl($i);
      $this->fetchDataFile($a, $file, $url);

      // witness file
      if ($this->problem->hasWitness) {
        $a = $this->problem->getTestWitness($i);
        $file = $this->getWitnessFileName($i);
        $url = $this->getWitnessUrl($i);
        $this->fetchDataFile($a, $file, $url);
      }
    }

    // grader file
    if ($this->problem->grader) {
      $a = $this->problem->getGrader();
      $file = $this->getGraderFileName();
      $url = $this->getGraderUrl();
      $this->fetchDataFile($a, $file, $url);
    }

    // source file
    $file = $this->getSourceFileName();
    $url = $this->getSourceUrl();
    $this->fetchDataFile($this->source, $file, $url);
  }

  /**
   * Compiles a file if necessary.
   * Returns a pair [$output, $exitCode].
   * Throws an exception if there source file is missing.
   **/
  function compileIfNewer($source, $extension, $binary) {
    if (!file_exists($source)) {
      throw new Exception();
    }
    if (!file_exists($binary) ||
        (filemtime($binary) < filemtime($source))) {
      print "* Compiling {$source} into {$binary}\n";
      $compiler = self::COMPILERS[$extension];
      $command = sprintf($compiler, $source, $binary);
      exec($command, $output, $exitCode);

      $output = array_slice($output, 0, self::COMPILER_OUTPUT_LINES);
      $output = implode("\n", $output);
      return [$output, $exitCode];
    }
  }

  function compileGrader() {
    if (!$this->problem->grader) {
      return;
    }
    try {
      list($output, $exitCode) = $this->compileIfNewer(
        $this->getGraderFileName(),
        $this->graderExtension,
        $this->getGraderBinary()
      );
    } catch (Exception $e) {
      throw new Exception(Source::STATUS_NO_GRADER);
    }

    if ($exitCode) {
      throw new Exception(Source::STATUS_GRADER_ERROR);
    }
  }

  function compileSource() {
    try {
      list($output, $exitCode) = $this->compileIfNewer(
        $this->getSourceFileName(),
        $this->source->extension,
        $this->getSourceBinary()
      );
    } catch (Exception $e) {
      throw new Exception(Source::STATUS_NO_SOURCE);
    }

    $this->source->compileLog = $output;
    $this->source->save();

    if ($exitCode) {
      throw new Exception(Source::STATUS_COMPILE_ERROR);
    }
  }

  function runAllTests() {
    for ($i = 1; $i <= $this->problem->numTests; $i++) {
      exec("rm -rf {$this->workDir}");
      @mkdir($this->workDir, 0777, true);

      // copy the binary and input file
      copy($this->getSourceBinary(), $this->getWorkBinary());
      copy($this->getInputFileName($i), $this->getWorkInputFile());

      // TODO run the program in jail

      // evaluate the output
      if ($this->problem->hasGrader) {
      } else {
        $command = sprintf('diff -qBbEa %s %s',
                           $this->getWitnessFileName($i),
                           $this->getWorkOutputFile());
        exec($command, $ignored, $exitCode);
      }

      exec("rm -rf {$this->workDir}");
    }
  }
}
