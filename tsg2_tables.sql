use lokidoki_tsg2;

create table if not exists thing (
	thingID integer not null auto_increment,
	userID char(255) not null,
	posx integer not null,
	posy integer not null,
	age integer,
	direction integer,
	energy float(10,2),
	genes text,
	ancestors text,
	primary key (thingID, userID),
	index thing_idx (posx,posy)
);
