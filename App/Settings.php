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
     * Load all user balance data of incomes & expenses in selected period
     * 
     * @return mixed Balance object with incomes & expenses associative arrays
     */
    public static function loadBalanceDataOfIncomesAndExpensesInSelectedPeriod($balance, $user, $date_begin, $date_end)
    {

        if ($user) {
            $all_incomes_stmt = BalanceSummary::getAllUserIncomesForSelectedPeriod($user->id, $date_begin, $date_end);

            foreach ($all_incomes_stmt as $row) {
                $balance->income_data[] = $row;
            }

            $all_expenses_stmt = BalanceSummary::getAllUserExpensesForSelectedPeriod($user->id, $date_begin, $date_end);

            foreach ($all_expenses_stmt as $row) {
                $balance->expense_data[] = $row;
            }

            $income_category_total_amount_stmt = BalanceSummary::getTotalAmountOfIncomesForEachCategoryInSelectedPeriod($user->id, $date_begin, $date_end);

            foreach ($income_category_total_amount_stmt as $row) {
                $balance->income_category_total_amount[] = $row;
            }

            $expense_category_total_amount_stmt = BalanceSummary::getTotalAmountOfExpensesForEachCategoryInSelectedPeriod($user->id, $date_begin, $date_end);

            foreach ($expense_category_total_amount_stmt as $row) {
                $balance->expense_category_total_amount[] = $row;
            }

            $balance->total_incomes = BalanceSummary::getTotalSumOfIncomesInSelectedPeriod($user->id, $date_begin, $date_end)["amount"];

            /*$balance->total_incomes = BalanceSummary::getTotalSumOfIncomesInSelectedPeriod($user->id, $date_begin, $date_end)[0]->amount; -- Dla zwracanego obiektu.*/

            $balance->total_expenses = BalanceSummary::getTotalSumOfExpensesInSelectedPeriod($user->id, $date_begin, $date_end)["amount"];

            $balance->final_balance = $balance->total_incomes - $balance->total_expenses;

        }

        return $balance;
    }

    /**
     * Download current month and year
     * 
     * @return string Month and year
     */
    public static function downloadCurrentMonthAndYear() {

        return date('F Y');
    }

}
