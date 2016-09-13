<?php

/**
 * This script extracts a coherent subset of the legacy database.
 * It requires access to the full (private) legacy database.
 * It creates another (public) database with the same schema.
 * Both databases must already exist and reside on the same server.
 * It uses Idiorm, not Paris, since we don't have models defined for the legacy database.
 * WARNING! The public database will be TRUNCATED. All existing data will be lost.
 **/

require_once __DIR__ . '/../lib/Util.php';

// configure these to suit your setup
$PRIVATE_DB_NAME = 'vianuarena';
$PUBLIC_DB = "mysql://root@localhost/vianuarena_public";
$USER_IDS = "(112, 208)";
$ROUND_IDS = "('2015-02-17-test-8', '2015-02-19-test-8', '2015-03-03-test-8', " .
           "'2015-03-26-test-8', '2015-04-01-test-8', '2015-04-08-test-8')";


/**************************************************************************/

$parts = Db::parseDsn($PUBLIC_DB);
ORM::configure(sprintf("mysql:host=%s;dbname=%s", $parts['host'], $parts['database']));
ORM::configure('username', $parts['user']);
ORM::configure('password', $parts['password']);

// drop and recreate the tables we need in the public DB
$tables = [
  'ia_file',
  'ia_job',
  'ia_job_test',
  'ia_parameter_value',
  'ia_rating',
  'ia_round',
  'ia_round_tags',
  'ia_round_task',
  'ia_score_user_round',
  'ia_score_user_round_task',
  'ia_tags',
  'ia_task',
  'ia_task_ratings',
  'ia_task_tags',
  'ia_textblock',
  'ia_user',
  'ia_user_round',
];

foreach ($tables as $table) {
  Log::info("Dropping and recreating table {$table}");
  Db::execute("drop table if exists {$table}");
  Db::execute("create table {$table} like {$PRIVATE_DB_NAME}.{$table}");
}

// copy tags
Log::info('Copying all tags');
Db::execute("insert into ia_tags " .
            "select * from {$PRIVATE_DB_NAME}.ia_tags");

// copy users
Log::info("Copying users {$USER_IDS}");
Db::execute("insert into ia_user " .
            "select * from {$PRIVATE_DB_NAME}.ia_user " .
            "where id in {$USER_IDS}");

// copy rounds
Log::info("Copying rounds {$ROUND_IDS}");
Db::execute("insert into ia_round " .
            "select * from {$PRIVATE_DB_NAME}.ia_round " .
            "where id in {$ROUND_IDS}");

// copy user rounds
Log::info('Copying user rounds');
Db::execute("insert into ia_user_round " .
            "select * from {$PRIVATE_DB_NAME}.ia_user_round " .
            "where user_id in {$USER_IDS} " .
            "and round_id in {$ROUND_IDS}");

// copy round tasks
Log::info('Copying round tasks');
Db::execute("insert into ia_round_task " .
            "select * from {$PRIVATE_DB_NAME}.ia_round_task " .
            "where round_id in {$ROUND_IDS}");

// copy tasks with open sources and tests
Log::info('Copying tasks with open sources and tests');
Db::execute("insert into ia_task " .
            "select * from {$PRIVATE_DB_NAME}.ia_task " .
            "where open_source " .
            "and open_tests");

// copy task ratings for the tasks above
Log::info('Copying task ratings for the tasks above');
Db::execute("insert into ia_task_ratings " .
            "select tr.* from {$PRIVATE_DB_NAME}.ia_task_ratings tr " .
            "join {$PRIVATE_DB_NAME}.ia_task t on tr.task_id = t.id " .
            "where tr.user_id in {$USER_IDS} " .
            "and open_source " .
            "and open_tests");

// copy task tags for the tasks above
Log::info('Copying task tags for the tasks above');
Db::execute("insert into ia_task_tags " .
            "select tt.* from {$PRIVATE_DB_NAME}.ia_task_tags tt " .
            "join {$PRIVATE_DB_NAME}.ia_task t on tt.task_id = t.id " .
            "where open_source " .
            "and open_tests");

// copy textblocks (task statements)
Log::info('Copying textblocks (task statements)');
Db::execute("insert into ia_textblock " .
            "select tb.* from {$PRIVATE_DB_NAME}.ia_textblock tb " .
            "join {$PRIVATE_DB_NAME}.ia_task t on tb.name = concat('problema/', t.id) " .
            "where open_source " .
            "and open_tests");

// copy parameter values for the tasks above
Log::info('Copying parameter values for the tasks above');
Db::execute("insert into ia_parameter_value " .
            "select pv.* from {$PRIVATE_DB_NAME}.ia_parameter_value pv " .
            "join {$PRIVATE_DB_NAME}.ia_task t on pv.object_type = 'task' " .
            "and pv.object_id = t.id " .
            "where open_source " .
            "and open_tests");

// copy parameter values for the rounds above
Log::info('Copying parameter values for the rounds above');
Db::execute("insert into ia_parameter_value " .
            "select * from {$PRIVATE_DB_NAME}.ia_parameter_value " .
            "where object_type = 'round' " .
            "and object_id in {$ROUND_IDS}");

// copy attachment references for the tasks above
Log::info('Copying attachment references for the tasks above');
Db::execute("insert into ia_file " .
            "select f.* from {$PRIVATE_DB_NAME}.ia_file f " .
            "join {$PRIVATE_DB_NAME}.ia_task t on f.page = concat('problema/', t.id) " .
            "where open_source " .
            "and open_tests");

// copy ratings
Log::info('Copying ratings');
Db::execute("insert into ia_rating " .
            "select * from {$PRIVATE_DB_NAME}.ia_rating " .
            "where user_id in {$USER_IDS} " .
            "and round_id in {$ROUND_IDS}");

// copy scores for rounds and tasks
Log::info('Copying round scores');
Db::execute("insert into ia_score_user_round " .
            "select * from {$PRIVATE_DB_NAME}.ia_score_user_round " .
            "where user_id in {$USER_IDS} " .
            "and round_id in {$ROUND_IDS}");

Log::info('Copying task scores');
Db::execute("insert into ia_score_user_round_task " .
            "select * from {$PRIVATE_DB_NAME}.ia_score_user_round_task " .
            "where user_id in {$USER_IDS} " .
            "and round_id in {$ROUND_IDS}");

// copy jobs
Log::info('Copying jobs');
Db::execute("insert into ia_job " .
            "select j.* from {$PRIVATE_DB_NAME}.ia_job j " .
            "join {$PRIVATE_DB_NAME}.ia_task t on j.task_id = t.id " .
            "where j.user_id in {$USER_IDS} " .
            "and open_source " .
            "and open_tests");

// copy job tests for the jobs above
Log::info('Copying job tests for the jobs above (this can take slightly longer)');
Db::execute("insert into ia_job_test " .
            "select test.* from {$PRIVATE_DB_NAME}.ia_job_test test " .
            "join {$PRIVATE_DB_NAME}.ia_job j on test.job_id = j.id " .
            "join {$PRIVATE_DB_NAME}.ia_task t on j.task_id = t.id " .
            "where j.user_id in {$USER_IDS} " .
            "and open_source " .
            "and open_tests");
