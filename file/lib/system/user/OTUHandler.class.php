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
		$sql = "DELETE FROM	wcf".WCF_N."_otu_blacklist
			".$condition;
				
		// save username on One-Time Username blacklist
		$stmt = \wcf\system\WCF::getDB()->prepareStatement($sql);
		$stmt->execute($condition->getParameters());
		
		$sql = "INSERT INTO	wcf".WCF_N."_otu_blacklist
					(username, time)
			VALUES		(?, ?)";
		$stmt = \wcf\system\WCF::getDB()->prepareStatement($sql);
		foreach ($usernames as $username) {
			$stmt->execute(array($username, TIME_NOW));
		}
		\wcf\system\WCF::getDB()->commitTransaction();
		
		// save the changed blacklist in database
		$this->rebuildOption();
	}
	
	/**
	 * Removes expired entries from One-Time Username blacklist
	 */
	public function prune() {
		if (OTU_BLACKLIST_LIFETIME == -1) return;
		
		$sql = "DELETE FROM	wcf".WCF_N."_otu_blacklist
			WHERE		time < ?";
		$stmt = \wcf\system\WCF::getDB()->prepareStatement($sql);
		$stmt->execute(array(TIME_NOW - OTU_BLACKLIST_LIFETIME * 86400));
		
		$this->rebuildOption();
	}
	
	/**
	 * Rebuilds the "register_forbidden_usernames" option
	 */
	public function rebuildOption() {
		// get options
		$options = \wcf\data\option\Option::getOptions();
		
		// delete One-Time-Usernames from WCF username blacklist
		$blacklist = \wcf\system\Regex::compile('(?:^|\n),One-Time-Username-Start-DO-NOT-REMOVE\n.*\n,One-Time-Username-End-DO-NOT-REMOVE(\n|$)', \wcf\system\Regex::DOT_ALL)->replace($options['REGISTER_FORBIDDEN_USERNAMES']->optionValue, '\\1');
		
		// list was broken, delete leftover start or end marks
		// everything on the list will be treated as blacklisted by hand!
		$blacklist = str_replace(array(',One-Time-Username-Start-DO-NOT-REMOVE', ',One-Time-Username-End-DO-NOT-REMOVE'), '', $blacklist);
		
		// read One-Time-Username blacklist from database
		$condition = new \wcf\system\database\util\PreparedStatementConditionBuilder();
		if (OTU_BLACKLIST_LIFETIME > -1) $condition->add('time > ?', array(TIME_NOW - OTU_BLACKLIST_LIFETIME * 86400));
		else $condition->add('1 = 1');
		
		$sql = "SELECT	username
			FROM	wcf".WCF_N."_otu_blacklist
			".$condition."
			ORDER	BY username ASC";
		$stmt = \wcf\system\WCF::getDB()->prepareStatement($sql);
		$stmt->execute($condition->getParameters());
		
		$otUsernames = '';
		while ($username = $stmt->fetchColumn()) {
			$otUsernames .= $username . "\n";
		}
		
		// add One-Time Usernames to blacklist
		if ($otUsernames !== '') {
			// leading comma, because it isn't a valid username
			$blacklist .= "\n,One-Time-Username-Start-DO-NOT-REMOVE\n";
			$blacklist .= $otUsernames;
			$blacklist .= ',One-Time-Username-End-DO-NOT-REMOVE';
		}
		
		// trim empty lines
		$blacklist = preg_replace("~\n+~", "\n", \wcf\util\StringUtil::trim($blacklist));
		
		// save blacklist
		$optionAction = new \wcf\data\option\OptionAction(array(), 'import', array('data' => array(
			'register_forbidden_usernames' => $blacklist
		)));
		$optionAction->executeAction();
	}
}
