CREATE TABLE IF NOT EXISTS USERS
(
	id       int(11) unsigned AUTO_INCREMENT NOT NULL PRIMARY KEY,
	login    varchar(50) NOT NULL,
	password varchar(50) NOT NULL,
	salt     varchar(10) NOT NULL
);


CREATE TABLE IF NOT EXISTS SECTIONS
(
	id       int(11) unsigned AUTO_INCREMENT NOT NULL PRIMARY KEY,
	name     varchar(50)      NOT NULL,
	position int(5)  unsigned NOT NULL,
	user_id  int(11) unsigned NOT NULL,
	FOREIGN KEY fk_section_user_id(user_id) REFERENCES USERS(id) ON UPDATE CASCADE ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS LINKS
(
	id         int(11) unsigned AUTO_INCREMENT NOT NULL PRIMARY KEY,
	name       varchar(100) NOT NULL,
	url        varchar(500) NOT NULL,
	position   int(5)  unsigned NOT NULL,
	section_id int(11) unsigned NOT NULL,
	user_id    int(11) unsigned NOT NULL,
	FOREIGN KEY fk_link_section_id(section_id) REFERENCES SECTIONS(id) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY fk_link_user_id(user_id)       REFERENCES USERS(id)    ON UPDATE CASCADE ON DELETE CASCADE
);