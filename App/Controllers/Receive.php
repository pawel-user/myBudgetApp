<?php

namespace App\Controllers;

ob_start();

use \App\Mail;
use \App\Flash;
use \App\Auth;
use App\Config;

/**
 * Home controller
 * 
 * PHP version 7.4
 */
class Receive extends \Core\Controller
{
    /**
     * Send email message to author
     */
    public function receiveMailAction()
    {
        $email = $_POST['email'];
        $subject = $_POST['subject'];
        $message = $_POST['message'];
        $html = $_POST['message'];

       if (Mail::receive($email, $subject, $message, $html)) {
            Flash::addMessage('Successfully sent your email message');
        } else {
            Flash::addMessage('Sending your email message failed', Flash::DANGER);
        }

        $this->redirect('/contact');
            
        //mail($to, $subject, $message);

/*// Connect to the mail server
$imap = imap_open('{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX', Config::MAIL_ADDRESS, Config::MAIL_PASSWORD);

// Retrieve the incoming mail
$messages = imap_search($imap, 'ALL');

// Process each incoming message
foreach ($messages as $message) {
    // Get the message header
    $header = imap_headerinfo($imap, $message);

    // Get the sender's email address
    $sender = $header->from[0]->mailbox . '@' . $header->from[0]->host;

    // Get the recipient's email address
    $recipient = $header->to[0]->mailbox . '@' . $header->to[0]->host;

    // Get the subject of the message
    $subject = $header->subject;

    // Get the body of the message
    $body = imap_fetchbody($imap, $message, 1);

    // Process the message
    // ...

    //var_dump($sender);
    //exit;
}*/

    }
}
