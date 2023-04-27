<?php

namespace wcf\data\user\otu\blacklist\entry;

use wcf\data\DatabaseObjectEditor;
use wcf\data\IEditableCachedObject;
use wcf\data\option\Option;
use wcf\data\option\OptionAction;
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\WCF;
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
     * @see \wcf\data\DatabaseObjectDecorator::$baseClass
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

        // read One-Time-Username blacklist from database
        $condition = new PreparedStatementConditionBuilder();
        if (OTU_BLACKLIST_LIFETIME > -1) {
            $condition->add('time > ?', [TIME_NOW - OTU_BLACKLIST_LIFETIME * 86400]);
        } else {
            $condition->add('1 = 1');
        }

        $sql = "SELECT	username
                FROM	wcf1_user_otu_blacklist_entry
                {$condition}
                ORDER	BY username ASC";
        $stmt = WCF::getDB()->prepare($sql);
        $stmt->execute($condition->getParameters());

        $otUsernames = '';
        while ($username = $stmt->fetchColumn()) {
            $otUsernames .= $username . "\n";
        }

        // add One-Time-Usernames to blacklist
        if ($otUsernames !== '') {
            // leading comma, because it isn't a valid username
            $blacklist .= "\n,One-Time-Username-Start-DO-NOT-REMOVE\n";
            $blacklist .= $otUsernames;
            $blacklist .= ',One-Time-Username-End-DO-NOT-REMOVE';
        }

        // trim empty lines
        $blacklist = \preg_replace("~\n+~", "\n", StringUtil::trim($blacklist));

        // save blacklist
        $optionAction = new OptionAction([], 'import', ['data' => [
            'register_forbidden_usernames' => $blacklist,
        ]]);
        $optionAction->executeAction();
    }
}
