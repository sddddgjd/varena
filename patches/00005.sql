alter table problem
  add userId int not null after statement,
  add key (userId);
update problem set userId = 1;
