<?php

namespace App\Controllers;

use Core\View;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\PaymentMethod;
use App\Flash;
use App\Auth;
use App\DataSetup;
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
        View::renderTemplate('Loss/new.html', ['expense_load' => Settings::loadUserExpenseNames(), 'payment_load' => Settings::loadUserPaymentMethods()]);
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
            View::renderTemplate('Loss/new.html', ['expense' => $expense, 'expense_load' => Settings::loadUserExpenseNames(), 'payment_load' => Settings::loadUserPaymentMethods()]);
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

        if (PaymentMethod::createPaymentMethod($userID, $paymentMethod)) {

            Flash::addMessage('A new payment method successfully created.');

            $this->redirect(Auth::getReturnToPage());
        } else {

            Flash::addMessage('Adding a new payment method failed.', Flash::DANGER);

            $this->redirect(Auth::getReturnToPage());
        }
    }

    /**
     * Select a button to perform a specific action for modify expense categories
     * 
     * @return void
     */
    public function selectCategoryAction()
    {
        $userID = $_SESSION['user_id'];
        $expense = new Expense($_GET);
        
        if ($_GET['name'] == '') {
            $this->redirect(Auth::getReturnToPage());
            Flash::addMessage('No expense category has been selected. Try again.', Flash::WARNING);
            exit;
        }

        $selected_expense_name = $expense->name;
        $categoryID = $expense->getExpenseCategoryAssignedToUserID($userID)['id'];

        switch ($_REQUEST['action']) {

            case 'edit': //action for edit button
                View::renderTemplate('Loss/edit_category.html', ['selected_expense_name' => $selected_expense_name, 'userID' => $userID, 'categoryID' => $categoryID]);
                break;

            case 'delete': //action for delete button
                View::renderTemplate('Loss/delete_category_confirm.html', ['selected_expense_name' => $selected_expense_name, 'userID' => $userID, 'categoryID' => $categoryID]);
                break;
        }
    }

    /**
     * Edit an existing user expense category
     * 
     * @return void
     */
    public function editCategoryAction()
    {
        $userID = intval($_POST['userID']);
        $categoryID = intval($_POST['categoryID']);
        $newCategoryName = $_POST['changed-name'];

        switch ($_REQUEST['action']) {
            case 'cancel': //action for cancel edit expense category and return to previous page
                Flash::addMessage('Editing of expense category cancelled.', Flash::DANGER);
                break;

            case 'confirm': //action for confirm edit expense category
                if (ExpenseCategory::editExpenseCategory($userID, $categoryID, $newCategoryName)) {

                    Flash::addMessage('Successfully edited expense category.');
                } else {

                    Flash::addMessage('Editing expense category failed.', Flash::DANGER);
                }
        }
        $this->redirect(Auth::getReturnToPage());
    }

    /**
     * Select a button to perform a specific action for modify payment methods
     * 
     * @return void
     */
    public function selectMethodAction()
    {
        $userID = $_SESSION['user_id'];
        $expense = new Expense($_GET);
        
        if ($_GET['name'] == '') {
            $this->redirect(Auth::getReturnToPage());
            Flash::addMessage('No payment method has been selected. Try again.', Flash::WARNING);
            exit;
        }

        $selected_payment_method = $expense->name;
        $paymentID = $expense->getPaymentMethodAssignedToUserID($userID)['id'];

        switch ($_REQUEST['action']) {

            case 'edit': //action for edit button
                View::renderTemplate('Loss/edit_payment_method.html', ['selected_payment_method' => $selected_payment_method, 'userID' => $userID, 'paymentID' => $paymentID]);
                break;

            case 'delete': //action for delete button
                View::renderTemplate('Loss/delete_payment_method_confirm.html', ['selected_payment_method' => $selected_payment_method, 'userID' => $userID, 'paymentID' => $paymentID]);
                break;
        }
    }

    /**
     * Edit an existing user payment method
     * 
     * @return void
     */
    public function editMethodAction()
    {
        $userID = intval($_POST['userID']);
        $paymentID = intval($_POST['paymentID']);
        $newPaymentMethod = $_POST['changed-method'];

        switch ($_REQUEST['action']) {
            case 'cancel': //action for cancel edit payment method and return to previous page
                Flash::addMessage('Editing of payment method cancelled.', Flash::DANGER);
                break;

            case 'confirm': //action for confirm edit payment method
                if (PaymentMethod::editPaymentMethod($userID, $paymentID, $newPaymentMethod)) {

                    Flash::addMessage('Successfully edited payment method.');
                } else {

                    Flash::addMessage('Editing payment method failed.', Flash::DANGER);
                }
        }
        $this->redirect(Auth::getReturnToPage());
    }

    /**
     * Remove an existing user expense category
     * 
     * @return void
     */
    public function removeCategoryAction()
    {
        $userID = intval($_GET['userID']);
        $categoryID = intval($_GET['categoryID']);

        switch ($_REQUEST['action']) {
            case 'confirm': //action for confirm delete expense category
                if (ExpenseCategory::checkRemoveExpenseCategory($userID, $categoryID)) {
                    Flash::addMessage('Successfully removed expense category.');
                } else {
                    Flash::addMessage('Removed expense category is not empty.', Flash::WARNING);
                    Flash::addMessage('Delete these items first or change the category for them.', Flash::INFO);
                }
                break;

            case 'cancel': //action for cancel delete expense category
                Flash::addMessage('Removal of expense category cancelled.', Flash::DANGER);
                break;
        }
        $this->redirect(Auth::getReturnToPage());
    }

    /**
     * Remove an existing user payment method
     * 
     * @return void
     */
    public function removeMethodAction()
    {
        $userID = intval($_GET['userID']);
        $paymentID = intval($_GET['paymentID']);

        switch ($_REQUEST['action']) {
            case 'confirm': //action for confirm delete payment method
                if (PaymentMethod::checkRemovePaymentMethod($userID, $paymentID)) {
                    Flash::addMessage('Successfully removed payment method.');
                } else {
                    Flash::addMessage('Removed payment method is not empty.', Flash::WARNING);
                    Flash::addMessage('Delete these items first or change the payment method for them.', Flash::INFO);
                }
                break;

            case 'cancel': //action for cancel delete payment method
                Flash::addMessage('Removal of payment method cancelled.', Flash::DANGER);
                break;
        }
        $this->redirect(Auth::getReturnToPage());
    }

    /**
     * Remove an existing user expense item
     * 
     * @return void
     */
    public function removeExpenseAction()
    {
        $expenseID = $_POST["expenseID"];

        Expense::removeUserExpenseSavedInDatabase($expenseID);

        DataSetup::orderExpenseTableItems();

        Auth::getPreviousPage();

        Flash::addMessage('Successfully removed expense item.');
    }

    /**
     * Display of the expense data editing form
     * 
     * @return void
     */
    public function editItemAction() {
        
        $userID = $_SESSION['user_id'];

        $expenseID = intval($_POST['expenseID']);
        $expenseCategory = $_POST['expenseCategory'];
        $expensePaymentMethod = $_POST['expensePaymentMethod'];
        $expenseAmount = $_POST['expenseAmount'];
        $expenseDate = $_POST['expenseDate'];
        $expenseComment = $_POST['expenseComment'];

        $expenseCategories_stmt = ExpenseCategory::getUserExpenseCategories($userID);

        foreach($expenseCategories_stmt as $row) {
            $expenseCategories[] = $row['name'];
        }

        $expensePaymentMethods_stmt = PaymentMethod::getUserPaymentMethods($userID);

        foreach($expensePaymentMethods_stmt as $row) {
            $paymentMethods[] = $row['name'];
        }

        View::renderTemplate('Loss/edit_item.html', ['expenseID' => $expenseID, 'expenseCategory' => $expenseCategory, 'expensePaymentMethod' => $expensePaymentMethod, 'expenseAmount' => $expenseAmount, 'expenseDate' => $expenseDate, 'expenseComment' => $expenseComment, 'setExpenseCategories' => $expenseCategories, 'setPaymentMethods' => $paymentMethods]); 

    }

    /** 
     * Edit an existing user expense item
     * 
     * @return void
     */
    public function editExpenseAction() {

        $userID = $_SESSION['user_id'];
        
        $expenseCategory = $_POST['expenseCategory'];
        $expensePaymentMethod = $_POST['expensePaymentMethod'];
        $expenseID = intval($_POST['expenseID']);
        $expenseAmount = floatval($_POST['expenseAmount']);
        $expenseDate = date('Y-m-d', strtotime($_POST['expenseDate']));
        $expenseComment = $_POST['expenseComment'];
        $expenseCategoryAssignedToUsersID = Expense::getExpenseIdAssignedToUser($userID, $expenseCategory)['id'];
        $expensePaymentMethodAssignedToUsersID = Expense::getPaymentMethodIdAssignedToUser($userID, $expensePaymentMethod)['id'];

        Expense::editUserExpenseSavedInDatabase($expenseID, $expenseAmount, $expenseDate, $expenseComment, $expenseCategoryAssignedToUsersID, $expensePaymentMethodAssignedToUsersID);

        //Auth::getPreviousPage();
        $this->redirect('/balance/summary');

        Flash::addMessage('Successfully edited expense item.');
    }
}