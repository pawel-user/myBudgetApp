<?php

namespace App\Controllers;

use Core\View;
use App\Models\Income;
use App\Models\IncomeCategory;
use App\Flash;
use App\Auth;
use App\Settings;
use App\DataSetup;

/**
 * Add & edit income controller
 * 
 * PHP version 8.2.6
 */

class Profit extends Authenticated
{
    /**
     * Show add income form
     * 
     * @return void
     */
    public function newAction()
    {
        View::renderTemplate('Profit/new.html', ['income_load' => Settings::loadUserIncomeNames()]);
    }

    /**
     * Add a new user income category from existing list of income categories
     * 
     * @return void
     */
    public function addAction()
    {
        $income = new Income($_POST);

        if ($income->save()) {

            Flash::addMessage('Income category successfully added.');

            $this->redirect('/profit/new');
        } else {
            View::renderTemplate('Profit/new.html', ['income' => $income, 'income_load' => Settings::loadUserIncomeNames()]);
        }
    }

    /**
     * Create a new user income category and add to list of income categories
     * 
     * @return void
     */
    public function createCategoryAction()
    {
        $incomeCategory = implode('', $_POST);
        $userID = $_SESSION['user_id'];

        if (IncomeCategory::createIncomeCategory($userID, $incomeCategory)) {

            Flash::addMessage('A new income category successfully created.');

            $this->redirect(Auth::getReturnToPage());
        } else {

            Flash::addMessage('Adding a new income category failed.', Flash::DANGER);

            $this->redirect(Auth::getReturnToPage());
        }
    }

    /**
     * Select a button to perform a specific action for income category
     * 
     * @return void
     */
    public function selectCategoryAction()
    {
        $userID = $_SESSION['user_id'];
        $income = new Income($_GET);

        if ($_GET['name'] == '') {
            $this->redirect(Auth::getReturnToPage());
            Flash::addMessage('No income category has been selected. Try again.', Flash::WARNING);
            exit;
        }

        $selected_income_name = $income->name;
        $categoryID = $income->getIncomeCategoryAssignedToUserID($userID)['id'];

        switch ($_REQUEST['action']) {

            case 'edit': //action for edit button
                View::renderTemplate('Profit/edit_category.html', ['selected_income_name' => $selected_income_name, 'userID' => $userID, 'categoryID' => $categoryID]);
                break;

            case 'delete': //action for delete button
                View::renderTemplate('Profit/delete_category_confirm.html', ['selected_income_name' => $selected_income_name, 'userID' => $userID, 'categoryID' => $categoryID]);
                break;
        }
    }

    /**
     * Edit an existing user income category
     * 
     * @return void
     */
    public function editCategoryAction()
    {
        $userID = intval($_POST['userID']);
        $categoryID = intval($_POST['categoryID']);
        $newCategoryName = $_POST['changed-name'];

        var_dump($_POST['changed-name']);
        exit;

        switch ($_REQUEST['action']) {
            case 'cancel': //action for cancel edit income category and return to previous page
                Flash::addMessage('Editing of income category cancelled.', Flash::DANGER);
                break;

            case 'confirm': //action for confirm edit income category
                if (IncomeCategory::editIncomeCategory($userID, $categoryID, $newCategoryName)) {

                    Flash::addMessage('Successfully edited income category.');
                } else {

                    Flash::addMessage('Editing income category failed.', Flash::DANGER);
                }
        }
        $this->redirect(Auth::getReturnToPage());
    }

    /**
     * Remove an existing user income category
     * 
     * @return void
     */
    public function removeCategoryAction()
    {
        $userID = intval($_GET['userID']);
        $categoryID = intval($_GET['categoryID']);

        switch ($_REQUEST['action']) {
            case 'confirm': //action for confirm delete income category
                if (IncomeCategory::checkRemoveIncomeCategory($userID, $categoryID)) {
                    Flash::addMessage('Successfully removed income category.');
                } else {
                    Flash::addMessage('Removed income category is not empty.', Flash::WARNING);
                    Flash::addMessage('Delete these items first or change the category for them.', Flash::INFO);
                }
                break;

            case 'cancel': //action for cancel deletion income category
                Flash::addMessage('Removal of income category cancelled.', Flash::DANGER);
                break;
        }
        $this->redirect(Auth::getReturnToPage());
    }

    /**
     * Remove an existing user income item
     * 
     * @return void
     */
    public function removeIncomeAction()
    {
        $incomeID = $_POST["incomeID"];

        Income::removeUserIncomeSavedInDatabase($incomeID);

        DataSetup::orderIncomeTableItems(); 

        Auth::getPreviousPage();

        //$this->redirect($url);
        Flash::addMessage('Successfully removed income item.');
    }
}
