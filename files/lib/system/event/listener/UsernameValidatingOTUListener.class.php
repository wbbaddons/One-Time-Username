<?php

namespace wcf\system\event\listener;

use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\WCF;

/**
 * Rejects usernames in the One-Time-Username blacklist.
 *
 * @author Tim Duesterhus
 * @copyright 2013 Maximilian Mader
 * @license BSD 3-Clause License <http://opensource.org/licenses/BSD-3-Clause>
 */
final class UsernameValidatingOTUListener
{
    public function __invoke(
        \wcf\system\user\event\UsernameValidating|\wcf\event\user\UsernameValidating $event
    ): void {
        // Check if the username is already blocked to prevent
        // an unnecessary database query.
        if ($event->defaultPrevented()) {
            return;
        }

        if ($this->isBlocked($event->username)) {
            $event->preventDefault();
        }
    }

    private function isBlocked(string $name): bool
    {
        $condition = new PreparedStatementConditionBuilder();
        $condition->add('username = ?', [$name]);
        if (OTU_BLACKLIST_LIFETIME > -1) {
            $condition->add('time > ?', [TIME_NOW - OTU_BLACKLIST_LIFETIME * 86400]);
        }

        $sql = "SELECT  COUNT(*)
                FROM    wcf1_user_otu_blacklist_entry
                {$condition}";
        $statement = WCF::getDB()->prepare($sql);
        $statement->execute($condition->getParameters());

        return $statement->fetchSingleColumn() > 0;
    }
}
