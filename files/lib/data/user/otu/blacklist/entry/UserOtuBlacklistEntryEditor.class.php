<?php

namespace wcf\data\user\otu\blacklist\entry;

use wcf\data\DatabaseObjectEditor;
use wcf\data\IEditableCachedObject;
use wcf\data\option\Option;
use wcf\data\option\OptionAction;
use wcf\util\StringUtil;

/**
 * Provides functions to edit OTU blacklist entries
 *
 * @author  Maximilian Mader
 * @copyright   2013 Maximilian Mader
 * @license BSD 3-Clause License <http://opensource.org/licenses/BSD-3-Clause>
 */
class UserOtuBlacklistEntryEditor extends DatabaseObjectEditor implements IEditableCachedObject
{
    /**
     * @inheritDoc
     */
    protected static $baseClass = UserOtuBlacklistEntry::class;

    /**
     * Clears the room cache.
     */
    public static function resetCache()
    {
        // get options
        $options = Option::getOptions();

        // delete One-Time-Usernames from WCF username blacklist
        $blacklist = UserOtuBlacklistEntry::replaceOTUTextList($options['REGISTER_FORBIDDEN_USERNAMES']->optionValue);

        // trim empty lines
        $blacklist = \preg_replace("~\n+~", "\n", StringUtil::trim($blacklist));

        // save blacklist
        $optionAction = new OptionAction([], 'import', ['data' => [
            'register_forbidden_usernames' => $blacklist,
        ]]);
        $optionAction->executeAction();
    }
}
