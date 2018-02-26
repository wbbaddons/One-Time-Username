DROP TABLE IF EXISTS wcf1_user_otu_blacklist_entry;
CREATE TABLE wcf1_user_otu_blacklist_entry (
	entryID		INT(10)		NOT NULL AUTO_INCREMENT PRIMARY KEY,
	username	VARCHAR(100)	NOT NULL,
	time		INT(10)		NOT NULL,
	userID		INT(10)		DEFAULT NULL,
	
	KEY (username),
	KEY (time)
);

ALTER TABLE wcf1_user_otu_blacklist_entry ADD FOREIGN KEY (userID) REFERENCES wcf1_user (userID) ON DELETE SET NULL;
