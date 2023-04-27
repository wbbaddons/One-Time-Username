<?php

namespace wcf\data\user\otu\blacklist\entry;

use wcf\data\DatabaseObjectList;

/**
 * Represents a list of user OTU blacklist entries.
 *
 * @author  Maximilian Mader
 * @copyright   2013 Maximilian Mader
 * @license BSD 3-Clause License <http://opensource.org/licenses/BSD-3-Clause>
 * @package be.bastelstu.max.wcf.otu
 * @subpackage  data.user.otu.blacklist.entry
 */
class UserOtuBlacklistEntryList extends DatabaseObjectList
{
    /**
     * @see \wcf\data\DatabaseObjectList::$className
     */
    public $className = UserOtuBlacklistEntry::class;

    /**
     * @see \wcf\data\DatabaseObjectList::$sqlSelects
     */
    public $sqlSelects = "user.username AS lastOwner";

    public function __construct()
    {
        parent::__construct();

        $this->sqlJoins = "LEFT JOIN wcf" . WCF_N . "_user user ON user.userID = user_otu_blacklist_entry.userID";
    }
}
