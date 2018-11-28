DROP TABLE users;
DROP TABLE posts;

CREATE TABLE users(
	username varchar(255),
	password varchar(255),
	fname varchar(255),
	lname varchar(255),
	email varchar(255),
	bio varchar(4096),
	PRIMARY KEY(username)
);

CREATE TABLE posts(
	id SERIAL,
	username varchar(255),
	content varchar(4096),
	likes int,
	time int,
	PRIMARY KEY(id)
);