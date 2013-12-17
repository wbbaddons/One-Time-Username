<?php
namespace be\bastelstu\max\wcf\otu;

/**
 * Handles installation of One-Time-Username.
 *
 * @author 	Maximilian Mader
 * @copyright	2013 Maximilian Mader
 * @license	BSD 3-Clause License <http://opensource.org/licenses/BSD-3-Clause>
 * @package	be.bastelstu.max.wcf.otu
 */
// @codingStandardsIgnoreFile

// little workaround, options have already been loaded and the constant won't be defined
if (!defined('OTU_BLACKLIST_LIFETIME')) define('OTU_BLACKLIST_LIFETIME', 182);

// We don't check for "lastUsernameChange > 0", if this isn't set (how ever this may happen) TIME_NOW will be used.
$sql = "SELECT	userID, oldUsername, lastUsernameChange
	FROM	wcf".WCF_N."_user
	WHERE	oldUsername <> ?
	ORDER	BY oldUsername ASC";
$stmt = \wcf\system\WCF::getDB()->prepareStatement($sql);
$stmt->execute(array(''));
$entries = array();
while ($row = $stmt->fetchArray()) {
	$entries[] = array('username' => $row['oldUsername'], 'time' => $row['lastUsernameChange'], 'userID' => $row['userID']);
}

// blacklist the usernames that have been used before
\wcf\system\WCF::getDB()->beginTransaction();
$sql = "INSERT INTO	wcf".WCF_N."_user_otu_blacklist_entry
			(username, time, userID)
	VALUES		(?, ?, ?)";
$stmt = \wcf\system\WCF::getDB()->prepareStatement($sql);
foreach ($entries as $entry) {
	$stmt->execute(array($entry['username'], ($entry['time'] > 0) ? $entry['time'] : TIME_NOW, $entry['userID']));
}
\wcf\system\WCF::getDB()->commitTransaction();

// rebuild the corresponding option
\wcf\data\user\otu\blacklist\entry\UserOtuBlacklistEntryEditor::resetCache();
