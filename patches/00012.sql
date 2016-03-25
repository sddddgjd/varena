update test set runningTime = runningTime * 1000;
alter table test change runningTime runningTime int not null;
