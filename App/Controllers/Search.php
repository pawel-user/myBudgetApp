<?php

namespace App\Controllers;

use \Core\View;
use App\Flash;
use App\Settings;
use App\Auth;
use App\Models\SearchResults;

/**
 * Search controller
 * 
 * PHP version 7.4
 */

#[\AllowDynamicProperties]
class Search extends \Core\Controller
{

    /**
     * Display source of the homepage of a website
     * 
     * @return void
     */
    public function displayResultAction()
    {
        $search = new SearchResults();

        $user = Auth::getUser();

        //var_dump($user->id);
        //exit;

        $keyword = $_POST['keyword'];

        View::renderTemplate('Search/results.html', ['search_income_results' => Settings::loadIncomeItemSearchResults($search, $user, $keyword), 'search_expense_results' => Settings::loadExpenseItemSearchResults($search, $user, $keyword), 'search_counter' => Settings::sumAllSearchResults($search)]);

    }
}
