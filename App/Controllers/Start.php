<?php

namespace App\Controllers;

use Core\View;

/**
 * Home controller
 * 
 * PHP version 7.4
 */
class Start extends \Core\Controller
{
    /**
     * Before filter
     * 
     * @return void
     */
    protected function before()
    {
        //echo "(before) ";
        //return false;
    }

    /**
     * After filter
     * 
     * @return void
     */
    protected function after()
    {
        //echo " (after)";
    }

    /**
     * How the index page
     * 
     * @return void
     */
    public function indexAction()
    {
        View::renderTemplate('Home/index.html');
    }

    /**
     * How the home page
     * 
     * @return void
     */
    public function homeAction()
    {
        View::renderTemplate('Start/home.html');
    }

    /**
     * How the contact page
     * 
     * @return void
     */
    public function contactAction()
    {
        View::renderTemplate('Home/contact.html');
    }

    /**
     * How the incomes page
     * 
     * @return void
     */
    public function incomesAction()
    {
        View::renderTemplate('Start/incomes.html');
    }

    /**
     * How the expenses page
     * 
     * @return void
     */
    public function expensesAction()
    {
        View::renderTemplate('Start/expenses.html');
    }
    
}
