<?php
namespace wcf\data\user\otu\blacklist\entry;

/**
 * Executes OTU-blacklist-related actions.
 *
 * @author 	Maximilian Mader
 * @copyright	2013 Maximilian Mader
 * @license	BSD 3-Clause License <http://opensource.org/licenses/BSD-3-Clause>
 * @package	be.bastelstu.max.wcf.otu
 * @subpackage	data.user.otu.blacklist.entry
 */
class UserOtuBlacklistEntryAction extends \wcf\data\AbstractDatabaseObjectAction {
	/**
	 * @see	\wcf\data\AbstractDatabaseObjectAction::$className
	 */
	protected $className = '\wcf\data\user\otu\blacklist\entry\UserOtuBlacklistEntryEditor';
	
	/**
	 * @see	\wcf\data\AbstractDatabaseObjectAction::$permissionsDelete
	 */
	protected $permissionsDelete = array('admin.system.canEditOption');
	
	
	/**
	 * Resets cache if any of the listed actions is invoked
	 * @var	array<string>
	 */
	protected $resetCache = array('create', 'delete', 'toggle', 'update', 'updatePosition', 'prune', 'bulkCreate');
	
	
	/**
	 * Adds the given usernames to the OTU-blacklist
	 */
	public function bulkCreate() {
		\wcf\system\WCF::getDB()->beginTransaction();
		// prevent duplicate entries
		$condition = new \wcf\system\database\util\PreparedStatementConditionBuilder();
		$condition->add('username IN(?)', array(array_map(function($element) {
			return $element['username'];
		}, $this->parameters['data'])));
		
		$sql = "SELECT	".call_user_func(array($this->className, 'getDatabaseTableIndexName'))."
			FROM	".call_user_func(array($this->className, 'getDatabaseTableName'))."
			".$condition."
			FOR UPDATE";
		$stmt = \wcf\system\WCF::getDB()->prepareStatement($sql);
		$stmt->execute($condition->getParameters());
		$entryIDs = array();
		while ($entryID = $stmt->fetchColumn()) $entryIDs[] = $entryID;
		call_user_func(array($this->className, 'deleteAll'), $entryIDs);
		
		foreach ($this->parameters['data'] as $entry) {
			call_user_func(array($this->className, 'create'), $entry);
		}
		
		\wcf\system\WCF::getDB()->commitTransaction();
	}
	
	/**
	 * Removes expired entries from One-Time-Username blacklist
	 * 
	 * @return	integer		Number of deleted usernames
	 */
	public function prune() {
		if (OTU_BLACKLIST_LIFETIME == -1) return 0;
		
		$sql = "SELECT	".call_user_func(array($this->className, 'getDatabaseTableIndexName'))."
			FROM	".call_user_func(array($this->className, 'getDatabaseTableName'))."
			WHERE	time < ?";
		$stmt = \wcf\system\WCF::getDB()->prepareStatement($sql);
		$stmt->execute(array(TIME_NOW - OTU_BLACKLIST_LIFETIME * 86400));
		$entryIDs = array();
		while ($entryID = $stmt->fetchColumn()) $entryIDs[] = $entryID;
		
		return call_user_func(array($this->className, 'deleteAll'), $entryIDs);
	}
}
