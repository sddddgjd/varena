alter table test add exitCode int not null after status;
alter table test change graderMessage message varchar(255) not null;
