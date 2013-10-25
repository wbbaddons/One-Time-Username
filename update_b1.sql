INSERT INTO wcf1_user_otu_blacklist_entry (username, time) SELECT username, time FROM wcf1_otu_blacklist;
DROP TABLE wcf1_user_otu_blacklist;
