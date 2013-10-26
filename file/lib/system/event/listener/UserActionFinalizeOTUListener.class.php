<?php
namespace wcf\system\event\listener;

/**
 * Adds usernames to One-Time-Username blacklist.
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
			$action = $eventObj->getActionName();
			$parameters = $eventObj->getParameters();
			
			if ($action == 'update' && isset($parameters['data']) && isset($parameters['data']['oldUsername'])) {
				// User has changed their username, therefore add the old username to One-Time-Username blacklist.
				$blacklistEntryAction = new \wcf\data\user\otu\blacklist\entry\UserOtuBlacklistEntryAction(array(), 'create', array('data' => array('username' => $parameters['data']['oldUsername'], 'time' => TIME_NOW)));
				$blacklistEntryAction->executeAction();
			}
			else if (($action == 'update' && isset($parameters['data']) && isset($parameters['data']['username'])) || $action == 'delete') {
				// Users have been updated or deleted, therefore blacklist their (old) usernames.
				// If updated, only add the usernames to the One-Time-Username blacklist
				// if username has been changed (username parameter is set).
				$usernames = array();
				foreach ($eventObj->getObjects() as $object) {
					if ($object instanceof \wcf\data\user\UserEditor) {
						$usernames[] = array('username' => $object->username, 'time' => TIME_NOW);
					}
				}
				
				$blacklistEntryAction = new \wcf\data\user\otu\blacklist\entry\UserOtuBlacklistEntryAction(array(), 'bulkCreate', array('data' => $usernames));
				$blacklistEntryAction->executeAction();
			}
		}
	}
}
