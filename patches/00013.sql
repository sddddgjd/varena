truncate table user;
alter table user
  add username varchar(255) not null after password,
  add unique key(username);
