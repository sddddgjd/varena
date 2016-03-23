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

  /**
   * Returns a Test object with as much information populated as is known.
   * This is tailored to jrun, in absence of a better standard.
   **/
  function jailedRun() {
    $command = sprintf("%s/scripts/jrun/jrun -p %s -d %s -m %d -t %d -w %d " .
                       "--block-syscalls-file %s/scripts/jrun/bad_syscalls",
                       Util::$rootPath,
                       $this->getWorkBinary(),
                       $this->workDir,
                       $this->problem->memoryLimit,
                       $this->problem->timeLimit * 1000,
                       $this->problem->timeLimit * 10000, // wall time
                       Util::$rootPath);
    $jailOutput = exec($command);

    // interpret jrun's output
    $regexp = '/^(?<verdict>OK|FAIL): ' .
            'time (?<time>\\d+)ms ' .
            'memory (?<memory>\\d+)kb ' .
            'status (?<exitCode>[^:]+): ' .
            '(?<message>.*)$/';
    preg_match($regexp, $jailOutput, $data);

    $t = Model::factory('Test')->create();
    $t->sourceId = $this->source->id;
    $t->exitCode = $data['exitCode'];
    $t->runningTime = $data['time'] / 1000;
    $t->memoryUsed = $data['memory'];
    if ($data['message'] != 'Execution successful.') {
      $t->message = $data['message'];
    }

    return $t;
  }

  /**
   * Runs the grader and decides between STATUS_PASSED and STATUS_GRADED.
   * Updates $t's status, score and message.
   **/
  function runGrader($t) {
    // copy the witness file, if it exists, and the grader binary
    copy($this->getGraderBinary(), $this->getWorkGraderBinary());
    chmod($this->getWorkGraderBinary(), 0755); // copy() loses the permissions
    if ($this->problem->hasWitness) {
      copy($this->getWitnessFileName($t->number), $this->getWorkWitnessFile());
    }

    // We need to use proc_open to capture both stdout and stderr.
    $descriptorspec = [
      0 => ["pipe", "r"],  // stdin
      1 => ["pipe", "w"],  // stdout
      2 => ["pipe", "w"],  // stderr
    ];
    $p = proc_open($this->getWorkGraderBinary(),
                   $descriptorspec,
                   $pipes,
                   $this->workDir);
    $stdout = trim(stream_get_contents($pipes[1]));
    $stderr = trim(stream_get_contents($pipes[2]));
    fclose($pipes[0]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    proc_close($p);

    $t->score = $stdout;
    $t->message = $stderr;
    $t->status = ($t->score == 100) ? Test::STATUS_PASSED : Test::STATUS_GRADED;
  }

  /**
   * Runs diff and decides between STATUS_PASSED and STATUS_WRONG_ANSWER.
   * Updates $t's status and score.
   **/
  function runDiff($t) {
    $command = sprintf('diff -qBbEa %s %s',
                       $this->getWitnessFileName($t->number),
                       $this->getWorkOutputFile());
    exec($command, $ignored, $exitCode);
    if ($exitCode) {
      $t->status = Test::STATUS_WRONG_ANSWER;
    } else {
      $t->status = Test::STATUS_PASSED;
      $t->score = 100;
    }
  }

  function runAllTests() {
    // purge old test records, just in case
    Test::delete_all_by_sourceId($this->source->id);

    for ($i = 1; $i <= $this->problem->numTests; $i++) {
      exec("rm -rf {$this->workDir}");
      @mkdir($this->workDir, 0777, true);

      // copy the binary and input file
      copy($this->getSourceBinary(), $this->getWorkBinary());
      chmod($this->getWorkBinary(), 0755); // copy() loses the permissions
      copy($this->getInputFileName($i), $this->getWorkInputFile());

      $t = $this->jailedRun();
      $t->number = $i;

      if ($t->runningTime > $this->problem->timeLimit) {
        $t->status = Test::STATUS_TLE;
      } else if ($t->memoryUsed > $this->problem->memoryLimit) {
        $t->status = Test::STATUS_MLE;
      } else if ($t->exitCode != 0) {
        $t->status = Test::STATUS_NONZERO;
      } else if ($t->message) {
        $t->status = Test::STATUS_JAILED;
      } else if (!file_exists($this->getWorkOutputFile())) {
        $t->status = Test::STATUS_NO_OUTPUT;
      } else if ($this->problem->grader) {
        $this->runGrader($t);
      } else {
        $this->runDiff($t);
      }

      $t->save();

      exec("rm -rf {$this->workDir}");
    }
  }

  /**
   * Compute Source->score based on Test->score and Problem->testGroups.
   */
  function computeScore() {
    $this->source->computeScore();
    $this->source->save();
  }
}
