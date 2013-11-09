create database tsg2db;
create user tsg2user@localhost identified by 'kantara12';
grant all privileges on tsg2db.* to tsg2user@localhost;
