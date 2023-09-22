<?php

namespace App\Controllers;

use Core\View;
use App\Auth;

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
        $this->requireLogin();

        View::renderTemplate('Start/home.html');
    }

    /**
     * How the contact page
     * 
     * @return void
     */
    public function contactAction()
    {
        $this->requireLogin();

        View::renderTemplate('Start/contact.html');
    }

    /**
     * How the incomes page
     * 
     * @return void
     */
    public function incomesAction()
    {
        $this->requireLogin();

        View::renderTemplate('Start/incomes.html');
    }

    /**
     * How the expenses page
     * 
     * @return void
     */
    public function expensesAction()
    {
        $this->requireLogin();

        View::renderTemplate('Start/expenses.html');
    }

    /**
     * How the balance page
     * 
     * @return void
     */
    public function balanceAction()
    {
        $this->requireLogin();

        View::renderTemplate('Start/balance.html');
    }
}
