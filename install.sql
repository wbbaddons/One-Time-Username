DROP TABLE IF EXISTS wcf1_user_otu_blacklist_entry;
CREATE TABLE wcf1_user_otu_blacklist_entry (
	username	VARCHAR(255)	NOT NULL PRIMARY KEY,
	time		INT(10)		NOT NULL,
	userID		INT(10)		DEFAULT NULL
);

ALTER TABLE wcf1_user_otu_blacklist_entry ADD FOREIGN KEY (userID) REFERENCES wcf1_user (userID) ON DELETE SET NULL;
