<?php

namespace App;

/**
 * Application configuration
 * 
 * PHP version 7.4
 */

 class Config {
    /**
     * Database host
     * @var string
     */
    const DB_HOST = 'localhost';

    /**
     * Database name
     * @var string
     */
    const DB_NAME = 'mybudget';

    /**
     * Database user
     * @var string
     */
    const DB_USER = 'mybudgetadmin';

    /**
     * Database password
     * @var string
     */
    const DB_PASSWORD = 'secret';

    /**
     * Show or hide error messages on screen
     * @var boolean
     */
    const SHOW_ERRORS = true;

    /**
     * Secret key for hashing
     * 
     * @var boolean
     */
    const SECRET_KEY = 'RMccOFnQR4RnddmRUkOQYuV1nzKwY0s1';

    /**
     * Mail hostname
     * @var string
     */
    const MAIL_HOSTNAME = 'smtp.gmail.com';

    /**
     * Mailer e-mail address
     * @var string
     */
    const MAIL_ADDRESS = 'testmailer1220@gmail.com';

    /**
     * Mailer password
     * @var string
     */
    const MAIL_PASSWORD = 'uxkk jvge qeni flpt';

 }