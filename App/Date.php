<?php

namespace App;

/**
 * Getting current system date
 *
 * PHP version 8.2
 */
class Date {

    public static function getCurrentDate() {
        $currentDate = date('Y-m-d');

        return $currentDate;
    }
}
