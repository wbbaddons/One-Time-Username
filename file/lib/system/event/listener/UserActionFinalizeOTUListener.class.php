<?php
namespace wcf\system\event\listener;

/**
 * Adds usernames to One-Time Username blacklist.
 *
 * @author 	Maximilian Mader
 * @copyright	2013 Maximilian Mader
 * @license	BSD 3-Clause License <http://opensource.org/licenses/BSD-3-Clause>
 * @package	be.bastelstu.max.wcf.otu
 * @subpackage	system.event.listener
 */
class UserActionFinalizeOTUListener implements \wcf\system\event\IEventListener {
	/**
	 * @see	\wcf\system\event\IEventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if ($className == 'wcf\data\user\UserAction' && $eventName == 'finalizeAction') {
			$parameters = $eventObj->getParameters();
			if (isset($parameters['data']) && isset($parameters['data']['username'])) {
				// add username to One-Time Username blacklist and rebuild WCF options
				\wcf\system\user\OTUHandler::getInstance()->blacklistUsername($parameters['data']['username']);
			}
		}
	}
}
