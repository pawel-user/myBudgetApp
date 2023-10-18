<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;

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

    /**
     * Send the password reset lin to the supplied email
     * 
     * @return void
     */
    public function requestResetAction() {
        User::sendPasswordReset($_POST['email']);

        View::renderTemplate('Password/reset_requested.html');
    }

    public function resetAction() {
        $token = $this->route_params['token'];

        $user = User::findByPasswordReset($token);

        if ($user) {
            View::renderTemplate('Password/reset.html');
        } else {
            echo "Password reset token invalid";
        }
    }
}