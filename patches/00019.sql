create table tag (
  id int not null auto_increment,
  parentId int not null,
  rank int not null,
  value varchar(255) not null,
  created int not null,
  modified int not null,
  primary key (id),
  key (value),
  key (parentId),
  key (rank)
);

create table problem_tag (
  id int not null auto_increment,
  problemId int not null,
  tagId int not null,
  rank int not null,
  created int not null,
  modified int not null,
  primary key (id),
  key (problemId, rank),
  key (tagId)
);
