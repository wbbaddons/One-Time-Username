<?php

namespace wcf\system\event\listener;

use wcf\acp\form\OptionForm;
use wcf\data\user\otu\blacklist\entry\UserOtuBlacklistEntry;
use wcf\data\user\otu\blacklist\entry\UserOtuBlacklistEntryEditor;
use wcf\system\option\OptionHandler;

/**
 * Hides the OTU-blacklist in option "register_forbidden_usernames".
 *
 * @author  Maximilian Mader
 * @copyright   2013 Maximilian Mader
 * @license BSD 3-Clause License <http://opensource.org/licenses/BSD-3-Clause>
 * @package be.bastelstu.max.wcf.otu
 * @subpackage  system.event.listener
 */
final class OptionEditOTUListener implements IParameterizedEventListener
{
    /**
     * @see \wcf\system\event\IEventListener::execute()
     */
    public function execute($eventObj, $className, $eventName, array &$_parameters)
    {
        if ($className == OptionHandler::class && $eventName == 'afterReadCache' && isset($eventObj->cachedOptions['register_forbidden_usernames'])) {
            $eventObj->cachedOptions['register_forbidden_usernames']->optionValue = UserOtuBlacklistEntry::replaceOTUTextList($eventObj->cachedOptions['register_forbidden_usernames']->optionValue);
        } elseif ($className == OptionForm::class && $eventName == 'saved') {
            UserOtuBlacklistEntryEditor::resetCache();
        }
    }
}
