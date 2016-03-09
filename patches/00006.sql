create table attachment (
  id int not null auto_increment,
  problemId int not null,
  userId int not null,
  name varchar(255),
  size int not null,
  created int not null,
  modified int not null,

  primary key (id),
  key (problemId),
  key (name)
);
