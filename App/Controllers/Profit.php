<?php

namespace App\Controllers;

use Core\View;
use App\Models\Income;
//use App\Models\IncomeCategory;
use App\Flash;
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
        /*$income = new Income();

        $userID = $_SESSION['user_id'];
        $income_stmt = IncomeCategory::getUserIncomeCategories($userID);

        foreach ($income_stmt as $row) {
             $income->income_names[] = $row['name'];
        }*/

        //Settings::loadUserIncomeNames();

        View::renderTemplate('Profit/new.html', ['income' => Settings::loadUserIncomeNames()]);
    }

    /**
     * Add a new user income
     * 
     * @return void
     */
    public function createAction()
    {
        $income = new Income($_POST);

        if ($income->save()) {

            Flash::addMessage('Income category successfully added.');

            $this->redirect('/profit/new');

        } else {
            View::renderTemplate('Profit/new.html', ['income' => $income]);
        }
    }
}
