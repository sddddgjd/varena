create table role (
  id int not null auto_increment,
  name varchar(255) not null,
  created int not null,
  modified int not null,
  primary key (id)
);

create table user_role (
  id int not null auto_increment,
  userId int not null,
  roleId int not null,
  created int not null,
  modified int not null,
  primary key (id),
  key (userId),
  key (roleId)
);

create table role_permission (
  id int not null auto_increment,
  roleId int not null,
  permission int not null,
  created int not null,
  modified int not null,
  primary key (id),
  key (roleId),
  key (permission)
);
