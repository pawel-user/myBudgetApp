<?php

namespace App\Controllers;

use \Core\View;

/**
 * Password controller
 * 
 * PHP version 7.4
 */
class Password extends \Core\Controller {
    /**
     * Show the forgotten password page
     * 
     * @return void
     */
    public function forgotAction() {
        View::renderTemplate('Password/forgot.html');
    }
}