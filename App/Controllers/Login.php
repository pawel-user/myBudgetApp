<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;

/**
 * Login controller
 * 
 * PHP version 7.4
 */

 class Login extends \Core\Controller {
    /**
     * Log in a user
     * 
     * @return void
     */
    public function createAction() {
        $user = User::findByEmail($_POST['email']);
        
        var_dump($user);
    }
 }