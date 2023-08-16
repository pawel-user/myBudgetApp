<?php

namespace App\Controllers;

use Core\View;
use App\Models\User;

/**
 * Signup controller
 * 
 * PHP version 7.4
 */
class Register extends \Core\Controller {
    /**
     * Show the signup page
     * 
     * @return void
     */
    public function newAction() {

        View::renderTemplate('Register/new.html');
    }

    /**
     * Sign up a new user
     * 
     * @return void
     */
    public function createAction() {
        $user = new User($_POST);

        if ($user->save()) {

            View::renderTemplate('Register/success.html');

        } else {
            var_dump($user->errors);
        }
    }
}
