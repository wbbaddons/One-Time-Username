<?php

namespace wcf\system\event\listener;

use wcf\data\user\otu\blacklist\entry\UserOtuBlacklistEntryAction;
use wcf\data\user\UserAction;
use wcf\data\user\UserEditor;

/**
 * Adds usernames to One-Time-Username blacklist.
 *
 * @author  Maximilian Mader
 * @copyright   2013 Maximilian Mader
 * @license BSD 3-Clause License <http://opensource.org/licenses/BSD-3-Clause>
 */
final class UserActionFinalizeOTUListener implements IParameterizedEventListener
{
    /**
     * @inheritDoc
     */
    public function execute($eventObj, $className, $eventName, array &$_parameters)
    {
        if ($className == UserAction::class && $eventName == 'finalizeAction') {
            $action = $eventObj->getActionName();
            $parameters = $eventObj->getParameters();

            if (($action == 'update' && isset($parameters['data']) && isset($parameters['data']['username'])) || $action == 'delete') {
                // Users have been updated or deleted, therefore blacklist their (old) usernames.
                // If updated, only add the usernames to the One-Time-Username blacklist
                // if username has been changed (username parameter is set).
                $entries = [];
                foreach ($eventObj->getObjects() as $object) {
                    if ($object instanceof UserEditor) {
                        // Skip if username has not been updated
                        //
                        // Also skip if username contains an asterisk (this is a wildcard!)
                        // see https://github.com/WoltLab/WCF/issues/1704
                        if ($action == 'update' && $parameters['data']['username'] == $object->username || \mb_strpos($object->username, '*') !== false) {
                            continue;
                        }

                        $entries[] = ['username' => $object->username, 'time' => TIME_NOW, 'userID' => ($action != 'delete') ? $object->userID : null];
                    }
                }

                $blacklistEntryAction = new UserOtuBlacklistEntryAction([], 'bulkCreate', ['data' => $entries]);
                $blacklistEntryAction->executeAction();
            }
        }
    }
}
