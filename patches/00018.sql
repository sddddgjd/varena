create table if not exists round (
  id int not null auto_increment,
  name varchar(255) not null,
  description blob not null,
  userId int not null,
  start int not null,
  duration int not null,
  created int not null,
  modified int not null,
  primary key (id),
  key(start),
  key(name)
);

create table if not exists round_problem (
  id int not null auto_increment,
  roundId int not null,
  problemId int not null,
  rank int not null,
  created int not null,
  modified int not null,
  primary key (id),
  key(roundId, rank),
  key(problemId)
);
