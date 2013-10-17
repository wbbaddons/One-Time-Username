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
		if (empty($username)) return;
		
		// delete username if OTU-blacklisted
		\wcf\system\WCF::getDB()->beginTransaction();
		$sql = "DELETE FROM
				wcf".WCF_N."_otu_blacklist
			WHERE
				username = ?";
				
		// save username on One-Time Username blacklist
		$stmt = \wcf\system\WCF::getDB()->prepareStatement($sql);
		$stmt->execute(array($username));
		
		$sql = "INSERT INTO
				wcf".WCF_N."_otu_blacklist (username, time)
			VALUES (?, ?)";
		$stmt = \wcf\system\WCF::getDB()->prepareStatement($sql);
		$stmt->execute(array($username, TIME_NOW));
		\wcf\system\WCF::getDB()->commitTransaction();
		
		// save the changed blacklist in database
		$this->rebuildOption();
	}
	
	/**
	 * Rebuilds the "register_forbidden_usernames" option
	 */
	public function rebuildOption() {
		// get options
		$options = \wcf\data\option\Option::getOptions();
		
		// delete One-Time-Usernames from WCF username blacklist
		$blacklist = \wcf\system\Regex::compile('(?:^|\n),One-Time-Username-Start\n.*\n,One-Time-Username-End(\n|$)', \wcf\system\Regex::DOT_ALL)->replace($options['REGISTER_FORBIDDEN_USERNAMES']->optionValue, '\\1');
		
		// list was broken, delete leftover start or end marks
		// everything on the list will be treated as blacklisted by hand!
		$blacklist = str_replace(array(',One-Time-Username-Start', ',One-Time-Username-End'), '', $blacklist);
		
		// read One-Time-Username blacklist from database
		$condition = new \wcf\system\database\util\PreparedStatementConditionBuilder();
		if (OTU_BLACKLIST_LIFETIME != -1) $condition->add('time > ?', array(TIME_NOW - OTU_BLACKLIST_LIFETIME * 86400));
		else $condition->add('1 = 1');
		
		$sql = "SELECT
				username
			FROM
				wcf".WCF_N."_otu_blacklist
			".$condition;
		$stmt = \wcf\system\WCF::getDB()->prepareStatement($sql);
		$stmt->execute($condition->getParameters());
		
		$otUsernames = '';
		while ($username = $stmt->fetchColumn()) {
			$otUsernames .= $username . "\n";
		}
		
		// add One-Time Usernames to blacklist
		if ($otUsernames !== '') {
			$blacklist .= "\n,One-Time-Username-Start\n";
			$blacklist .= $otUsernames;
			$blacklist .= ',One-Time-Username-End';
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
