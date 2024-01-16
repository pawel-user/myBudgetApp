<?php

namespace App\Controllers;

use Core\View;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\PaymentCategory;
use App\Flash;
use App\Auth;
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
     * Add a new user expense category from existing list of income categories
     * 
     * @return void
     */
    public function addAction()
    {
        $expense = new Expense($_POST);

        if ($expense->save()) {

            Flash::addMessage('Expense category successfully added.');

            $this->redirect('/loss/new');
        } else {
            View::renderTemplate('Loss/new.html', ['expense' => $expense]);
        }
    }

    /**
     * Create a new user expense category and add to list of expense categories
     * 
     * @return void
     */
    public function createCategoryAction()
    {
        $expenseCategory = implode('', $_POST);
        $userID = $_SESSION['user_id'];

        if (ExpenseCategory::createExpenseCategory($userID, $expenseCategory)) {

            Flash::addMessage('A new expense category successfully created.');

            $this->redirect(Auth::getReturnToPage());
        } else {

            Flash::addMessage('Adding a new expense category failed.', Flash::DANGER);

            $this->redirect(Auth::getReturnToPage());
        }
    }

    /**
     * Create a new user payment method and add to list of payment methods
     * 
     * @return void
     */
    public function createMethodAction()
    {
        $paymentMethod = implode('', $_POST);
        $userID = $_SESSION['user_id'];

        if (PaymentCategory::createPaymentMethod($userID, $paymentMethod)) {

            Flash::addMessage('A new payment method successfully created.');

            $this->redirect(Auth::getReturnToPage());
        } else {

            Flash::addMessage('Adding a new payment method failed.', Flash::DANGER);

            $this->redirect(Auth::getReturnToPage());
        }
    }
}