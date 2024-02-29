<?php

namespace App\Controllers;

use \App\Mail;
use \App\Flash;

/**
 * Home controller
 * 
 * PHP version 7.4
 */
class Send extends \Core\Controller
{
    /**
     * Send email message to author
     */
    public function sendMailAction()
    {

        $email = $_POST['email'];
        $subject = $_POST['subject'];
        $message = $_POST['message'];
        $html = $_POST['message'];

        Mail::send($email, $subject, $message, $html);

        Flash::addMessage('Successfully sent your email message');
        $this->redirect('/');
    }
}
