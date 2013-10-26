<?php
namespace wcf\acp\page;
use wcf\page\SortablePage;

/**
 * Lists the blacklisted usernames
 *
 * @author 	Maximilian Mader
 * @copyright	2013 Maximilian Mader
 * @license	BSD 3-Clause License <http://opensource.org/licenses/BSD-3-Clause>
 * @package	be.bastelstu.max.wcf.otu
 * @subpackage	acp.page
 * @category	Community Framework
 */
class OTUListPage extends SortablePage {
	/**
	 * @see	\wcf\page\AbstractPage::$activeMenuItem
	 */
	public $activeMenuItem = 'wcf.acp.menu.link.otu.list';
	
	/**
	 * @see	\wcf\page\SortablePage::$defaultSortField
	 */
	public $defaultSortField = 'username';
	
	/**
	 * @see	\wcf\page\AbstractPage::$neededPermissions
	 */
	public $neededPermissions = array('admin.system.canEditOption');
	
	/**
	 * @see	\wcf\page\MultipleLinkPage::$objectListClassName
	 */
	public $objectListClassName = '\wcf\data\user\otu\blacklist\entry\UserOtuBlacklistEntryList';
	
	/**
	 * @see	\wcf\page\AbstractPage::$templateName
	 */
	public $templateName = 'otuList';
	
	/**
	 * @see	\wcf\page\SortablePage::$validSortFields
	 */
	public $validSortFields = array('username', 'time', 'lastOwner', 'userID');
}
