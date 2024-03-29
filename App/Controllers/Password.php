<?php

namespace App\Controllers;

ob_start();        

use \Core\View;
use \App\Models\User;
use \App\Flash;
use \App\Auth;

/**
 * Password controller
 * 
 * PHP version 7.4
 */
class Password extends \Core\Controller
{
    /**
     * Show the forgotten password page
     * 
     * @return void
     */
    public function forgotAction()
    {
        View::renderTemplate('Password/forgot.html');
    }

    /**
     * Send the password reset link to the supplied email
     * 
     * @return void
     */
    public function requestResetAction()
    {
        if (User::sendPasswordReset($_POST['email'])) {
            $this->redirect('/password/show-send-email-message');
        } else {
            Flash::addMessage('Sending link to change password failed', Flash::DANGER);
            Flash::addMessage('There is no registered user with the email address provided.', Flash::INFO);
            $this->redirect('/password/forgot');
        }
    }

    public function showSendEmailMessageAction()
    {
        Flash::addMessage('Successfully sent a link to change your password');

        View::renderTemplate('Password/reset_requested.html');
    }

    /**
     * Show the reset password form
     * 
     * @return void
     */
    public function resetAction()
    {
        $token = $this->route_params['token'];

        $user = $this->getUserOrExit($token);

        View::renderTemplate('Password/reset.html', [
            'token' => $token
        ]);
    }

    /**
     * Reset the user's password
     * 
     * @return void
     */
    public function resetPasswordAction()
    {
        $token = $_POST['token'];

        $user = $this->getUserOrExit($token);

        if ($user->resetPassword($_POST['password'], $_POST['password_confirmation'])) {

            View::renderTemplate('Password/reset_success.html');
        } else {
            View::renderTemplate('Password/reset.html', [
                'token' => $token,
                'user' => $user
            ]);
        }
    }

    /**
     * Rename the user's password
     * 
     * @return void
     */
    public function renamePasswordAction()
    {
        $user = Auth::getUser();

        if ($user->resetPassword($_POST['password'], $_POST['password_confirmation'])) {
            Flash::addMessage('Password rename successfully.');        
        } else {
            Flash::addMessage('Password rename failed. Try again.', Flash::DANGER);
        }

        View::renderTemplate('Password/notification.html', ['user' => $user]);
    }

    /**
     * Find the user model associated with the password reset token, or end the request with a message
     * 
     * @param string $token Password reset token sent to user
     * 
     * @return mixed User object if found and the token hasn't expired, null otherwise
     */
    protected function getUserOrExit($token)
    {
        $user = User::findByPasswordReset($token);

        if ($user) {
            return $user;
        } else {
            View::renderTemplate('Password/token_expired.html');
            exit;
        }
    }
}
