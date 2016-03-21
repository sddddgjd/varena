create table test (
  id int not null auto_increment,
  sourceId int not null,
  number int not null,
  status int not null,
  runningTime decimal(6,3) not null,
  memoryUsed int not null,
  score int not null,
  graderMessage varchar(255) not null,
  created int not null,
  modified int not null,

  primary key (id),
  key (sourceId, number)
);
