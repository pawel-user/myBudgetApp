<?php

namespace App;

use App\Models\Income;
use App\Models\IncomeCategory;
use App\Auth;

/**
 * Getting current income or expense categories for registered user
 * 
 * PHP version 8.2.6
 */

class Settings {
    /**
     * Load user income names 
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
}