<?php

namespace App\Controllers;

use Core\View;
use App\Models\Income;


/**
 * Add & edit income controller
 * 
 * PHP version 8.2
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

        View::renderTemplate('Profit/new.html');
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

            $this->redirect('/profit/success');
        } else {
            View::renderTemplate('Profit/new.html', ['income' => $income]);
        }
    }
}
