use lokidoki_tsg2;

create table if not exists thing (
	thingID integer not null auto_increment,
	userID char(255) not null,
	x integer not null,
	y integer not null,
	age integer not null default '0',
	direction integer not null default '0',
	energy float(10,2),
	genes text,
	ancestors text,
	primary key (thingID, userID),
	index thing_idx (x,y)
);
