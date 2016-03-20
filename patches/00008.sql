alter table problem
  add numTests int not null after userId,
  add testGroups varchar(255) not null after numTests,
  add hasWitness boolean not null after testGroups,
  add evalFile varchar(255) not null after hasWitness,
  add timeLimit decimal(5,2) not null after evalFile,
  add memoryLimit int not null after timeLimit;
