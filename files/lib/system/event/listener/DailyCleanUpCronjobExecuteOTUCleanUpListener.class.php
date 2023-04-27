<?php

namespace wcf\system\event\listener;

use wcf\data\user\otu\blacklist\entry\UserOtuBlacklistEntryAction;

/**
 * Vaporizes unneeded data.
 *
 * @author  Maximilian Mader
 * @copyright   2013 Maximilian Mader
 * @license BSD 3-Clause License <http://opensource.org/licenses/BSD-3-Clause>
 */
final class DailyCleanUpCronjobExecuteOTUCleanUpListener implements IParameterizedEventListener
{
    /**
     * @see \wcf\system\event\IEventListener::execute()
     */
    public function execute($eventObj, $className, $eventName, array &$_parameters)
    {
        $blacklistEntryAction = new UserOtuBlacklistEntryAction([], 'prune');
        $blacklistEntryAction->executeAction();
    }
}
