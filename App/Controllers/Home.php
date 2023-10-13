<?php

namespace App\Controllers;

use Core\View;
use \App\Models\User;

/**
 * Home controller
 * 
 * PHP version 7.4
 */
class Home extends \Core\Controller {
    /**
     * Before filter
     * 
     * @return void
     */
    protected function before() {
        //echo "(before) ";
        //return false;
    }

    /**
     * After filter
     * 
     * @return void
     */
    protected function after() {
        //echo " (after)";
    }

    /**
     * How the index page
     * 
     * @return void
     */
    public function indexAction() {
    //    \App\Mail::send('pawel.bochacik.programista@gmail.com', 'Test', 'This is a test', '<h1>This is a test</h1>');
        View::renderTemplate('Home/index.html');
    }

    /**
     * How the contact page
     * 
     * @return void
     */
    public function contactAction() {
        View::renderTemplate('Home/contact.html');
    }

    /**
     * How the change password page
     * 
     * @return void
     */
    public function newpasswordAction() {
        View::renderTemplate('Home/newpassword.html');
    }
}