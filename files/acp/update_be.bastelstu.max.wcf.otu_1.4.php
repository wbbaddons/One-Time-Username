<?php

// phpcs:disable PSR1.Files.SideEffects

/**
 * Clear any values stored in the REGISTER_FORBIDDEN_USERNAMES option.
 *
 * @author  Tim Duesterhus
 * @copyright   2024 Maximilian Mader
 * @license BSD 3-Clause License <http://opensource.org/licenses/BSD-3-Clause>
 */

use wcf\data\user\otu\blacklist\entry\UserOtuBlacklistEntryEditor;

UserOtuBlacklistEntryEditor::resetCache();
