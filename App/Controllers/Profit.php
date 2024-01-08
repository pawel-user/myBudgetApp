<?php

namespace App\Controllers;

use Core\View;
use App\Models\Income;
use App\Models\IncomeCategory;
use App\Flash;
use App\Auth;
use App\Settings;

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
        View::renderTemplate('Profit/new.html', ['income' => Settings::loadUserIncomeNames()]);
    }

    /**
     * Add a new user income
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
            View::renderTemplate('Profit/new.html', ['income' => $income]);
        }
    }

    /**
     * Add a new user income category
     * 
     * @return void
     */
    public function createAction()
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
     * Select a button to perform a specific action 
     * 
     * @return void
     */
    public function selectAction()
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
                echo ("Delete case");
                break;
        }
    }

    /**
     * Edit an existing user income category
     * 
     * @return void
     */
    public function editAction()
    {
        $userID = $_POST['userID'];
        $categoryID = $_POST['categoryID'];
        $newCategoryName = $_POST['changed-name'];

        if (IncomeCategory::editIncomeCategory($userID, $categoryID, $newCategoryName)) {

            Flash::addMessage('Successfully edited income category.');

            $this->redirect(Auth::getReturnToPage());
        } else {

            Flash::addMessage('Editing income category failed.', Flash::DANGER);

            $this->redirect(Auth::getReturnToPage());
        }
    }
}
