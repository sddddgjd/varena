<?php

define('PORT', 4556);

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
  while (true) {
    print "Waiting...\n";
    pcntl_sigwaitinfo([SIGUSR1]);
    print "SIGUSR1 received\n";
    for ($i = 0; $i < 500000000; $i++);
    print "Done working.\n";
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
  socket_bind($sock, '127.0.0.1', PORT)
    or die("Socket bind error.\n");
  socket_listen($sock, 3)
    or die("Socket listen error.\n");

  while (true) {
    $accept = socket_accept($sock) or die("Could not accept incoming connection.\n");
    socket_close($accept);
    posix_kill(posix_getppid(), SIGUSR1);
  }
}
