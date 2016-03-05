create table problem (
  id int not null auto_increment,
  name varchar(255) not null,
  statement blob not null,
  created int not null,
  modified int not null,

  primary key(id),
  unique key(name)
);

insert into problem set name = 'adunare', statement = 'Se dau două numere; să se calculeze suma lor.', created = unix_timestamp(), modified = unix_timestamp();
insert into problem set name = 'scădere', statement = 'Se dau două numere; să se calculeze diferența lor.', created = unix_timestamp(), modified = unix_timestamp();
