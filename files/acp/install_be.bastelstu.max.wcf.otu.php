<?php

// phpcs:disable PSR1.Files.SideEffects

/**
 * Handles installation of One-Time-Username.
 *
 * @author  Maximilian Mader
 * @copyright   2013 Maximilian Mader
 * @license BSD 3-Clause License <http://opensource.org/licenses/BSD-3-Clause>
 */

use wcf\system\WCF;

// We don't check for "lastUsernameChange > 0", if this isn't set (how ever this may happen) TIME_NOW will be used.
$sql = "SELECT      userID,
                    oldUsername,
                    lastUsernameChange
        FROM        wcf1_user
        WHERE       oldUsername <> ?
        ORDER BY    oldUsername ASC";
$stmt = WCF::getDB()->prepare($sql);
$stmt->execute(['']);
$entries = [];
while ($row = $stmt->fetchArray()) {
    $entries[] = ['username' => $row['oldUsername'], 'time' => $row['lastUsernameChange'], 'userID' => $row['userID']];
}

// blacklist the usernames that have been used before
WCF::getDB()->beginTransaction();
$sql = "INSERT INTO wcf1_user_otu_blacklist_entry
                    (username, time, userID)
        VALUES      (?, ?, ?)";
$stmt = WCF::getDB()->prepare($sql);
foreach ($entries as $entry) {
    $stmt->execute([$entry['username'], ($entry['time'] > 0) ? $entry['time'] : TIME_NOW, $entry['userID']]);
}
WCF::getDB()->commitTransaction();
