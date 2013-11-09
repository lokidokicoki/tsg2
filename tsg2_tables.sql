use tsg2db;

create table if not exists thing (
	thingID integer not null auto_increment,
	posx integer not null,
	posy integer not null,
	age integer,
	direction integer,
	energy float(10,2),
	genes text,
	ancestors text,
	primary key (thingID),
	index thing_idx (posx,posy)
);
