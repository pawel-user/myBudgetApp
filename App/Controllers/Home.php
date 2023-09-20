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

    /**
     * Log in a user
     * 
     * @return void
     */
    /**public function createAction() {
        $user = User::findByEmail($_POST['email']);
        
        var_dump($user);
    }*/

}