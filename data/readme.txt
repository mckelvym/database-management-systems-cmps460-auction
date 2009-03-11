This directory contains the initial data that will be in the database,
as well as the files to create the relevant tables and load the data
into the database.

For example, a file like 'db.create' would have entries like:

--- drop existing tables if present ---
drop table if exists admin;

--- create tables ---
create table admin(
	id 	int(4) not null auto_increment,
	user_name	varchar(50),
	password	varchar(50),
	primary key (id)
);

A file like 'db.load' will load the data from local file, for example
it would have entries like:

select 'Loading admin';
load data local infile 'admin.data'
ignore
into table admin
fields terminated by ',';
-- lines terminated by '\r\n';

The file 'admin.data' from the previous example could have the
following data:
  
1,admin,admin
2,user,user

