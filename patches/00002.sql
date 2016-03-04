alter table user add password varchar(255) not null after email;
alter table user modify email varchar(255) not null;
alter table user modify name varchar(255) not null;
alter table user modify admin int not null;
alter table user drop index `name`;
