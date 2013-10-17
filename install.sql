DROP TABLE IF EXISTS wcf1_otu_blacklist;
CREATE TABLE wcf1_otu_blacklist (
	username	VARCHAR(255)	NOT NULL PRIMARY KEY,
	time		INT(10)		NOT NULL
);
