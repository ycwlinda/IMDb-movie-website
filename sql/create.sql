DROP DATABASE IF EXISTS CS143;
CREATE DATABASE CS143;
USE CS143;

CREATE TABLE Movie (
	id		INT not null,
	title	VARCHAR(100) not null, 
	year	INT not null,
	rating  VARCHAR(10),
	company VARCHAR(50) not null,
	PRIMARY KEY (id),
	CHECK (length(title)>0)
)ENGINE = INNODB;

CREATE TABLE Actor (
	id  	INT not null,
	last  	VARCHAR(20) not null,
	first  	VARCHAR(20) not null,
	sex     VARCHAR(6) not null,
	dob		date not null,
	dod		date,
	PRIMARY KEY (id),
	CHECK (length(last)>0),
	CHECK (length(first)>0),
	CHECK((dob is not null and dod is not null and dob < dod) or (dod is null))
)ENGINE = INNODB;

CREATE TABLE Director(
	id  	INT not null,
	last  	VARCHAR(20) not null,
	first  	VARCHAR(20) not null,
	dob		date not null,
	dod		date,
	PRIMARY KEY (id),
	CHECK (length(last)>0),
	CHECK (length(first)>0),
	CHECK ((dob is not null and dod is not null and dob < dod) or (dod is null))
)ENGINE = INNODB;

CREATE TABLE MovieGenre(
	mid    INT not null,
	genre  VARCHAR(20) not null,
	PRIMARY KEY (mid, genre),
	Foreign key (mid) references Movie(id)

)ENGINE = INNODB;

CREATE TABLE MovieDirector(
	mid    INT not null,
	did    INT not null,
	PRIMARY KEY (mid,did),
	Foreign key (mid) references Movie(id),
	Foreign key (did) references Director(id)
)ENGINE = INNODB;

CREATE TABLE MovieActor(
	mid    INT not null,
	aid    INT not null,
	role   VARCHAR(50) not null,
	PRIMARY KEY (mid,aid,role),
	Foreign key (mid) references Movie(id),
	Foreign key (aid) references Actor(id)
)ENGINE = INNODB;

CREATE TABLE Review(
	name 	VARCHAR(20) not null,
	time 	TIMESTAMP not null,
	mid     INT not null,
	rating  INT not null,
	comment VARCHAR(500),
	PRIMARY KEY (mid,name),
	Foreign key (mid) references Movie(id),
	Check(rating>=0 and rating <=5)
)ENGINE = INNODB;

CREATE TABLE MaxPersonID(
	id   INT not null
)ENGINE = INNODB;

CREATE TABLE MaxMovieID(
	id   INT not null
)ENGINE = INNODB;













