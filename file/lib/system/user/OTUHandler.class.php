<?php
namespace wcf\system\user;

/**
 * Provides functions for management of One-Time Username blacklist 
 *
 * @author 	Maximilian Mader
 * @copyright	2013 Maximilian Mader
 * @license	BSD 3-Clause License <http://opensource.org/licenses/BSD-3-Clause>
 * @package	be.bastelstu.max.wcf.otu
 * @subpackage	system.user
 */
class OTUHandler extends \wcf\system\SingletonFactory {
	/**
	 * Adds the given username to the blacklist or updates its timestamp
	 * 
	 * @param	string	$username
	 */
	public function blacklistUsername($username) {
		return $this->blacklistUsernames(array($username));
	}
	
	/**
	 * Adds the given username to the blacklist or updates its timestamp
	 * 
	 * @param	string	$username
	 */
	public function blacklistUsernames(array $usernames) {
		if (empty($usernames)) return;
		
		// delete username if OTU-blacklisted
		\wcf\system\WCF::getDB()->beginTransaction();
		$condition = new \wcf\system\database\util\PreparedStatementConditionBuilder();
		$condition->add('username IN (?)', array($usernames));
		$sql = "DELETE FROM	wcf".WCF_N."_user_otu_blacklist_entry
			".$condition;
				
		// save username on One-Time Username blacklist
		$stmt = \wcf\system\WCF::getDB()->prepareStatement($sql);
		$stmt->execute($condition->getParameters());
		
		$sql = "INSERT INTO	wcf".WCF_N."_user_otu_blacklist_entry
					(username, time)
			VALUES		(?, ?)";
		$stmt = \wcf\system\WCF::getDB()->prepareStatement($sql);
		foreach ($usernames as $username) {
			$stmt->execute(array($username, TIME_NOW));
		}
		\wcf\system\WCF::getDB()->commitTransaction();
		
		// save the changed blacklist in database
		\wcf\data\user\otu\blacklist\entry\UserOtuBlacklistEntryEditor::resetCache();
	}
}
