create table email_token (
  id int not null auto_increment,
  token char(32) default null,
  userId int not null,
  created int not null,
  modified int not null,
  primary key (id),
  key(token),
  key(userId)
);
