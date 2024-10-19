<?php

use wcf\system\event\EventHandler;
use wcf\system\event\listener\UsernameValidatingOTUListener;

/**
 * Registers the UsernameValidatingOTUListener.
 *
 * @author Tim Duesterhus
 * @copyright 2013 Maximilian Mader
 * @license BSD 3-Clause License <http://opensource.org/licenses/BSD-3-Clause>
 */
return static function (): void {
    $eventHandler = EventHandler::getInstance();
    $eventHandler->register(\wcf\system\user\event\UsernameValidating::class, UsernameValidatingOTUListener::class);
    $eventHandler->register(\wcf\event\user\UsernameValidating::class, UsernameValidatingOTUListener::class);
};
