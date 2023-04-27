<?php

namespace wcf\acp\page;

use wcf\page\SortablePage;

/**
 * Lists the blacklisted usernames
 *
 * @author  Maximilian Mader
 * @copyright   2013 Maximilian Mader
 * @license BSD 3-Clause License <http://opensource.org/licenses/BSD-3-Clause>
 * @package be.bastelstu.max.wcf.otu
 * @subpackage  acp.page
 * @category    Community Framework
 */
class OtuListPage extends SortablePage
{
    /**
     * @inheritDoc
     */
    public $activeMenuItem = 'wcf.acp.menu.link.user.management.otu';

    /**
     * @inheritDoc
     */
    public $defaultSortField = 'username';

    /**
     * @inheritDoc
     */
    public $neededPermissions = [ 'admin.configuration.canEditOption' ];

    /**
     * @inheritDoc
     */
    public $objectListClassName = \wcf\data\user\otu\blacklist\entry\UserOtuBlacklistEntryList::class;

    /**
     * @inheritDoc
     */
    public $templateName = 'otuList';

    /**
     * @inheritDoc
     */
    public $validSortFields = [ 'username', 'time', 'lastOwner', 'userID', 'entryID' ];
}
