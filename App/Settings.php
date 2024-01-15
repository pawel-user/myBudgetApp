<?php

namespace App;

use App\Models\Income;
use App\Models\Expense;
use App\Models\IncomeCategory;
use App\Models\ExpenseCategory;
use App\Models\PaymentCategory;
use App\Auth;

/**
 * Getting current income, expense categories and payment category methods for registered user
 * 
 * PHP version 8.2.6
 */

class Settings {
    
    /**
     * Load user income names
     * 
     * @return mixed Income object
     */
    public static function loadUserIncomeNames() {
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
    public static function loadUserExpenseNames() {
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
    public static function loadUserPaymentMethods() {
        $payment = new Expense();

        $user = Auth::getUser();

        if ($user) {
            $payment_stmt = PaymentCategory::getUserPaymentMethods($user->id);

            foreach ($payment_stmt as $row) {
                 $payment->payment_methods[] = $row['name'];
            }
        }

    return $payment;
    }
}