alter table problem
  add publicSources boolean default false after testGroups,
  add publicTests boolean default false after publicSources,
  add feedbackTests varchar(255)  default null after publicTests,
  add contest varchar(255) after feedbackTests,
  add year int after contest,
  add grade varchar(255) after year;
