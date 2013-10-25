<?php
namespace wcf\data\user\otu\blacklist\entry;

/**
 * Represents a list of user OTU blacklist entries.
 *
 * @author 	Maximilian Mader
 * @copyright	2013 Maximilian Mader
 * @license	BSD 3-Clause License <http://opensource.org/licenses/BSD-3-Clause>
 * @package	be.bastelstu.max.wcf.otu
 * @subpackage	data.user.otu.blacklist.entry
 */
class UserOtuBlacklistEntryList extends \wcf\data\DatabaseObjectList {
	/**
	 * @see	\wcf\data\DatabaseObjectList::$className
	 */
	public $className = '\wcf\data\user\otu\blacklist\entry\UserOtuBlacklistEntry';
}
