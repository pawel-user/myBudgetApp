<?php

namespace App;

/**
 * Flash notification messages: messages for one-time display using the session for storage between requests
 * 
 * PHP version 7.4
 */
class Flash {
   /**
    * Add a message
    *
    * @param string $message The message content
    *
    * @return void
    */
    public static function addMessage($message) {
        // Create array in the session if it doesn't already exist
        if (! isset($_SESSION['flash_notifications'])) {
            $_SESSION['flash_notifications'] = [];
        }

        // Append the message to the array
        $_SESSION['flash_notifications'][] = $message;
    }

    /**
     * Get all the messages
     * 
     * @return mixed An array with all the messages of null if name set
     */
    public static function getMessages() {
        if (isset($_SESSION['flash_notifications'])) {
            $messages = $_SESSION['flash_notifications'];
            unset($_SESSION['flash_notifications']);

            return $messages;
        }
    }
}