create table `user_desc` (
  id int not null auto_increment,
  userId int not null,
  description text,
  html text,
  created int not null,
  modified int not null,

  primary key (id),
  key (userId)
);
