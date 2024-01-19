<?php

namespace App;

/**
 * Date format
 *
 * PHP version 8.2
 */
class Date {

    /**
     * Getting current system date
     * 
     * @return string The date format
     */
    public static function getCurrentDate() {
        $currentDate = date('Y-m-d');

        return $currentDate;
    }
}
