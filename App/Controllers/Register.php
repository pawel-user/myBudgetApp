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

            header('Location: http://' . $_SERVER['HTTP_HOST'] . '/register/success', true, 303);
            exit;

        } else {
            View::renderTemplate('Register/new.html', ['user' => $user]);
        }
    }
    /**
     * Show the signup success page
     *
     * @return void
     */
    public function successAction()
    {
        View::renderTemplate('Register/success.html');
    }
}