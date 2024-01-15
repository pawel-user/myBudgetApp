<?php

namespace App\Controllers;

use Core\View;
use App\Models\Expense;
use App\Flash;
use App\Settings;

/**
 * Add & edit expense controller
 * 
 * PHP version 8.2.6
 */
class Loss extends Authenticated {
    /**
     * Show add expense form
     * 
     * @return void
     */
    public function newAction()
    {
        View::renderTemplate('Loss/new.html', ['expense' => Settings::loadUserExpenseNames(), 'payment' => Settings::loadUserPaymentMethods()]);
    }

    /**
     * Add a new user income
     * 
     * @return void
     */
    /**public function addAction()
    {
        $expense = new Expense($_POST);

        if ($expense->save()) {

            Flash::addMessage('Expense category successfully added.');

            $this->redirect('/loss/new');
        } else {
            View::renderTemplate('Loss/new.html', ['expense' => $expense]);
        }
    }*/
}