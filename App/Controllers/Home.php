<?php

namespace App\Controllers;

use Core\View;

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
     * How the register page
     * 
     * @return void
     */
    public function registerAction() {
        View::renderTemplate('Home/register.html');
    }

}