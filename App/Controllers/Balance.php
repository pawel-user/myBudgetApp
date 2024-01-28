<?php

namespace App\Controllers;

use Core\View;
use App\Settings;

class Balance extends Authenticated {
    /**
     * Show summary all incomes and expenses in the form of tables and a pie chart
     * 
     * @return void
     */
    public function summaryAction()
    {
        View::renderTemplate('Balance/summary.html', ['balance_load' => Settings::loadBalanceDataOfIncomesAndExpensesInCurrentMonth(), 'current_month_and_year' => Settings::downloadCurrentMonthAndYear()]);
    }

}