drop table login_cookie;

create table auth_token (
  id int not null auto_increment,
  selector char(12),
  token char(64),
  userId int not null,
  created int not null,
  modified int not null,

  primary key (id)
);
