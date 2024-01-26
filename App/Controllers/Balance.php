<?php

namespace App\Controllers;

use App\Models\BalanceSummary;
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
        $balance = new BalanceSummary();
        $balance = Settings::loadTotalUserIncomesAndExpensesForCurrentMonth();
        View::renderTemplate('Balance/summary.html', ['incomes_load' => $balance->income_data, 'expenses_load' => $balance->expense_data]);
    }

}