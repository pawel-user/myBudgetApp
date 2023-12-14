<?php

namespace App\Controllers;

use Core\View;
use App\Models\User;
use App\Models\IncomeCategory;

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

            $this->redirect('/register/success');

            $user->sendActivationEmail();

            $userID = $user->getUserID()->id;

            IncomeCategory::loadDefaultIncomeCategories($userID);
    
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

    /**
     * Activate a new account
     * 
     * @return void
     */
    public function activateAction() {
        User::activate($this->route_params['token']);
        
        $this->redirect('/register/activated');
    }

    /**
     * Show the activation success page
     * 
     * @return void
     */
    public function activatedAction() {
        View::renderTemplate('Register/activated.html');
    }
}
