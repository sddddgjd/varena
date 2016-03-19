<?php

require_once __DIR__ . '/../lib/Util.php';

if (Config::get('eval.index') < 0) {
  die("This installation isn't configured to run an eval loop.\n");
}

$pid = pcntl_fork();

if ($pid == -1) {
  // could not fork
  die("Well, fork me.\n");
} else if ($pid) {
  // parent
  evalLoop();
} else {
  // child
  pingListener();
}

/************************** eval loop process *****************************/

function evalLoop() {
  pcntl_signal(SIGUSR1, SIG_IGN);

  $mod = count(Config::get('eval.ip'));
  $rem = Config::get('eval.index');

  while (true) {
    do {
      // Find a new or pending job
      $source = Model::factory('Source')
              ->where_in('status', [Source::STATUS_NEW, Source::STATUS_PENDING])
              ->where_raw('id % ? = ?', [$mod, $rem])
              ->order_by_asc('id')
              ->find_one();
      if ($source) {
        evalSource($source);
      }
    } while ($source);
    print "Waiting...\n";

    pcntl_sigwaitinfo([SIGUSR1]);
    print "SIGUSR1 received\n";
  }
}

/************************** server listener process ***********************/

/**
 * Non-threaded listener for job notifications. Since this connection is only
 * used internally, and messages are zero-length, we shouldn't need threading.
 **/
function pingListener() {
  ob_implicit_flush();
  $sock = socket_create(AF_INET, SOCK_STREAM, 0)
        or die("Socket create error.\n");
  socket_set_option($sock, SOL_SOCKET, SO_REUSEADDR, 1);
  socket_bind($sock, '127.0.0.1', Config::get('eval.port'))
    or die("Socket bind error.\n");
  socket_listen($sock, 3)
    or die("Socket listen error.\n");

  while (true) {
    $accept = socket_accept($sock) or die("Could not accept incoming connection.\n");
    socket_close($accept);
    posix_kill(posix_getppid(), SIGUSR1);
  }
}

/************************** actual evaluation ***************************/

function evalSource($s) {
  printf("Evaluating source %d submitted by [%s] for problem [%s]\n",
         $s->id, $s->getUser()->name, $s->getProblem()->name);
  $s->status = Source::STATUS_PENDING;
  $s->save();
  // ...
  for ($i = 0; $i < 1000000000; $i++);
  $s->status = Source::STATUS_DONE;
  $s->save();
}
