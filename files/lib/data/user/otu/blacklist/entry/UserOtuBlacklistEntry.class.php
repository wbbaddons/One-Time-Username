<?php

namespace wcf\data\user\otu\blacklist\entry;

use wcf\data\DatabaseObject;
use wcf\system\Regex;

/**
 * Represents an user OTU blacklist entry.
 *
 * @author  Maximilian Mader
 * @copyright   2013 Maximilian Mader
 * @license BSD 3-Clause License <http://opensource.org/licenses/BSD-3-Clause>
 */
class UserOtuBlacklistEntry extends DatabaseObject
{
    /**
     * @inheritDoc
     */
    protected static $databaseTableName = 'user_otu_blacklist_entry';

    /**
     * @inheritDoc
     */
    protected static $databaseTableIndexName = 'entryID';

    /**
     * Removes contents between OTU start and end marks (e.g. ",One-Time-Username-Start-DO-NOT-REMOVE").
     *
     * @return string Text without contents between OTU marks
     */
    public static function replaceOTUTextList($text, $replacement = '\\1')
    {
        static $regex = null;

        if ($regex === null) {
            $regex = new Regex('(?:^|\n),One-Time-Username-Start-DO-NOT-REMOVE\n.*\n,One-Time-Username-End-DO-NOT-REMOVE(\n|$)', Regex::DOT_ALL);
        }

        $text = $regex->replace($text, $replacement);

        // list was broken, delete leftover start or end marks
        // everything on the list will be treated as blacklisted by hand!
        return \str_replace([',One-Time-Username-Start-DO-NOT-REMOVE', ',One-Time-Username-End-DO-NOT-REMOVE'], '', $text);
    }

    /**
     * Returns the time this entry expires or `null` if it does not expire.
     */
    public function getExpiry(): \DateTimeImmutable|null
    {
        if (OTU_BLACKLIST_LIFETIME < 0) {
            return null;
        }

        return new \DateTimeImmutable('@' . ($this->time + OTU_BLACKLIST_LIFETIME * 86400));
    }
}
