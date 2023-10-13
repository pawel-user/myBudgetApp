<?php

namespace App;

//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

use App\Config;

/**
 * Mail
 * 
 * PHP version 7.4
 */
class Mail
{
    /**
     * Send a message
     * 
     * @param string $to Recipient
     * @param string $subject Subject
     * @param string $text Text-only content of the message
     * @param string $html Content of the message
     * 
     * @return mixed
     */
    public static function send($to, $subject, $text, $html)
    {
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = Config::MAIL_HOSTNAME;                  //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = Config::MAIL_ADDRESS;                   //SMTP username
            $mail->Password   = Config::MAIL_PASSWORD;                  //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            //$mail->setFrom('from@example.com', 'Mailer');
            $mail->setFrom(Config::MAIL_ADDRESS, 'Mailer');             //Add a mailer
            $mail->addAddress($to, 'Recipient');                        //Add a recipient
            //$mail->addAddress('joe@example.net', 'Joe User');         //Add a recipient
            //$mail->addAddress('ellen@example.com');                   //Name is optional
            //$mail->addReplyTo('info@example.com', 'Information');
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');

            //Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $subject;                            //'Here is the subject';
            $mail->Body    = $html;                               //'This is the HTML message body <b>in bold!</b>';
            $mail->AltBody = $text;                               //'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
