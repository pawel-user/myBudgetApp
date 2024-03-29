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
        
        View::renderTemplate('Balance/summary.html', ['balance_load' => Settings::loadBalanceDataOfIncomesAndExpensesInSelectedPeriod($balance, $user, $date_begin, $date_end),'period_with_month_and_year' => Settings::downloadCurrentMonthWithYear()]);
    }

    /**
     * Show summary all incomes and expenses in the form of tables and a pie chart
     * 
     * @return void
     */
    public function summaryPreviousMonthAction()
    {
        $balance = new BalanceSummary();

        $user = Auth::getUser();

        $date_begin = date('Y-m-01', strtotime('-1 month'));
        $date_end = date('Y-m-t', strtotime('-1 month'));
        
        View::renderTemplate('Balance/summary.html', ['balance_load' => Settings::loadBalanceDataOfIncomesAndExpensesInSelectedPeriod($balance, $user, $date_begin, $date_end),'period_with_month_and_year' => Settings::downloadPreviousMonthWithYear()]);
    }

    /**
     * Show summary all incomes and expenses in the form of tables and a pie chart
     * 
     * @return void
     */
    public function summaryCurrentYearAction()
    {
        $balance = new BalanceSummary();

        $user = Auth::getUser();

        $date_begin = date('Y-01-01');
        $date_end = date('Y-m-t');
        
        View::renderTemplate('Balance/summary.html', ['balance_load' => Settings::loadBalanceDataOfIncomesAndExpensesInSelectedPeriod($balance, $user, $date_begin, $date_end),'period_with_month_and_year' => Settings::downloadPeriodForCurrentYear()]);
    }

    /**
     * Show summary all incomes and expenses in the form of tables and a pie chart
     * 
     * @return void
     */
    public function summarySelectedPeriodAction()
    {
        $balance = new BalanceSummary();

        $user = Auth::getUser();

        $index = 0;

        foreach ($_POST as $row) {
            $date[$index++] = $row;
        }

        $date_begin = $date[0];
        $date_end = $date[1];
        
        View::renderTemplate('Balance/summary.html', ['balance_load' => Settings::loadBalanceDataOfIncomesAndExpensesInSelectedPeriod($balance, $user, $date_begin, $date_end),'period_with_month_and_year' => Settings::downloadCustomPeriod($date_begin, $date_end)]);
    }
}