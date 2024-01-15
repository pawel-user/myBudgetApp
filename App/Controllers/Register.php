<?php

namespace App\Controllers;

use Core\View;
use App\Models\User;
use App\Models\IncomeCategory;
use App\Models\ExpenseCategory;
use App\Models\PaymentCategory;

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
     * @return mixed User object
     */
    public function createAction() {
        $user = new User($_POST);

        if ($user->save()) {

            $this->redirect('/register/success');

            $user->sendActivationEmail();

            $user->getUserID()->id;

        } else {
            View::renderTemplate('Register/new.html', ['user' => $user]);
        }
        return $user;
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

        $activation_token = $this->route_params['token'];

        $user = User::findUserIdByHashedToken($activation_token);
        $userID = $user->id;

        IncomeCategory::loadDefaultIncomeCategories($userID);
        ExpenseCategory::loadDefaultExpenseCategories($userID);
        PaymentCategory::loadDefaultPaymentMethods($userID);

        User::activate($activation_token);

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
