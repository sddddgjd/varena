insert into tag
  set parentId = 0,
      rank = 0,
      value = 'placeholder',
      created = unix_timestamp(),
      modified = unix_timestamp();
