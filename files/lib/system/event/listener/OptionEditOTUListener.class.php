<?php
namespace wcf\system\event\listener;

/**
 * Hides the OTU-blacklist in option "register_forbidden_usernames".
 *
 * @author 	Maximilian Mader
 * @copyright	2013 Maximilian Mader
 * @license	BSD 3-Clause License <http://opensource.org/licenses/BSD-3-Clause>
 * @package	be.bastelstu.max.wcf.otu
 * @subpackage	system.event.listener
 */
class OptionEditOTUListener implements \wcf\system\event\IEventListener {
	/**
	 * @see	\wcf\system\event\IEventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if ($className == 'wcf\system\option\OptionHandler' && $eventName == 'afterReadCache' && isset($eventObj->cachedOptions['register_forbidden_usernames'])) {
			$eventObj->cachedOptions['register_forbidden_usernames']->optionValue = \wcf\data\user\otu\blacklist\entry\UserOtuBlacklistEntry::replaceOTUTextList($eventObj->cachedOptions['register_forbidden_usernames']->optionValue);
		}
		else if ($className == 'wcf\acp\form\OptionForm' && $eventName == 'saved') {
			\wcf\data\user\otu\blacklist\entry\UserOtuBlacklistEntryEditor::resetCache();
		}
	}
}
