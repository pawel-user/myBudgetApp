<?php

namespace App;

use App\Models\Income;
use App\Models\Expense;
use App\Models\IncomeCategory;
use App\Models\ExpenseCategory;
use App\Models\PaymentMethod;
use App\Models\BalanceSummary;
use App\Auth;

/**
 * Getting current income, expense categories and payment category methods for registered user
 * 
 * PHP version 8.2.6
 */

class Settings
{

    /**
     * Load user income names
     * 
     * @return mixed Income object
     */
    public static function loadUserIncomeNames()
    {
        $income = new Income();

        $user = Auth::getUser();

        if ($user) {
            $income_stmt = IncomeCategory::getUserIncomeCategories($user->id);

            foreach ($income_stmt as $row) {
                $income->income_names[] = $row['name'];
            }
        }

        return $income;
    }

    /**
     * Load user expense names
     * 
     * @return mixed Expense object
     */
    public static function loadUserExpenseNames()
    {
        $expense = new Expense();

        $user = Auth::getUser();

        if ($user) {
            $expense_stmt = ExpenseCategory::getUserExpenseCategories($user->id);

            foreach ($expense_stmt as $row) {
                $expense->expense_names[] = $row['name'];
            }
        }

        return $expense;
    }

    /**
     * Load user payment methods names
     * 
     * @return mixed Payment object
     */
    public static function loadUserPaymentMethods()
    {
        $payment = new Expense();

        $user = Auth::getUser();

        if ($user) {
            $payment_stmt = PaymentMethod::getUserPaymentMethods($user->id);

            foreach ($payment_stmt as $row) {
                $payment->payment_methods[] = $row['name'];
            }
        }

        return $payment;
    }

    /**
     * Load user total incomes & expenses for current month
     * 
     * @return mixed Balance object with incomes & expenses associative arrays
     */
    public static function loadTotalUserIncomesAndExpensesForCurrentMonth()
    {
        $balance = new BalanceSummary();

        $user = Auth::getUser();

        $date_begin = date('Y-m-01');
        $date_end = date('Y-m-t');

        if ($user) {
            $total_incomes_stmt = BalanceSummary::getTotalUserIncomesForSelectedPeriod($user->id, $date_begin, $date_end);

            foreach ($total_incomes_stmt as $row) {
                $balance->income_data[] = $row;
            }

            $total_expenses_stmt = BalanceSummary::getTotalUserExpensesForSelectedPeriod($user->id, $date_begin, $date_end);

            foreach ($total_expenses_stmt as $row) {
                $balance->expense_data[] = $row;
            }
        }

        //var_dump($balance->expense_data);
        //exit;
        return $balance;
    }

}
