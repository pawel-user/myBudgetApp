<?php

namespace App\Controllers;

use Core\View;
use App\Settings;
use App\Models\BalanceSummary;
use App\Auth;

class Balance extends Authenticated {
    /**
     * Show summary all incomes and expenses in the form of tables and a pie chart
     * 
     * @return void
     */
    public function summaryAction()
    {
        $balance = new BalanceSummary();

        $user = Auth::getUser();

        $date_begin = date('Y-m-01');
        $date_end = date('Y-m-t');
        
        View::renderTemplate('Balance/summary.html', ['balance_load' => Settings::loadBalanceDataOfIncomesAndExpensesInSelectedPeriod($balance, $user, $date_begin, $date_end),'current_month_and_year' => Settings::downloadCurrentMonthAndYear($balance, $user, $date_begin, $date_end)]);
    }

}