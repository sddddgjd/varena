create table `source` (
  id int not null auto_increment,
  problemId int not null,
  userId int not null,
  sourceCode mediumblob not null,
  extension varchar(10) not null,
  status int not null,
  compileLog blob not null,
  score int not null,
  created int not null,
  modified int not null,

  primary key (id),
  key (problemId),
  key (userId),
  key (created)
);
