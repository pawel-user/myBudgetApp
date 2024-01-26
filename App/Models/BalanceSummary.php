<?php

namespace App\Models;

use PDO;

/**
 * User incomes & expenses table
 * 
 * PHP version 8.2.6
 */

class BalanceSummary extends \Core\Model
{
    /**
     * Total user income data for the selected period
     */
    public $income_data = [];

    /**
     * Total user expense data for the selected period
     */
    public $expense_data = [];

    /**
     * Class constructor
     * 
     * @param array $data Initial property values
     * 
     * @return void
     */
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }
    /**
     * Get user's total income data by user_id for the selected period
     * 
     * @return array
     */
    public static function getTotalUserIncomesForSelectedPeriod($userID, $date_begin, $date_end)
    {
        $sql = 'SELECT 
                    incomes_category_assigned_to_users.name AS category,
                    incomes.amount AS amount,
                    incomes.date_of_income,
                    incomes.income_comment
                FROM incomes_category_assigned_to_users
                    INNER JOIN incomes ON incomes.income_category_assigned_to_user_id = incomes_category_assigned_to_users.id
                WHERE incomes.user_id = :userID AND incomes.date_of_income BETWEEN :date_begin AND :date_end
                ORDER BY category';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':userID', $userID, PDO::PARAM_INT);
        $stmt->bindValue(':date_begin', $date_begin, PDO::PARAM_STR);
        $stmt->bindValue(':date_end', $date_end, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get user's total expense data by user_id for the selected period
     * 
     * @return array
     */
    public static function getTotalUserExpensesForSelectedPeriod($userID, $date_begin, $date_end)
    {
        $sql = 'SELECT 
                    expenses_category_assigned_to_users.name AS category,
                    expenses.amount AS amount,
                    expenses.date_of_expense,
                    expenses.expense_comment
                FROM expenses_category_assigned_to_users
                    INNER JOIN expenses ON expenses.expense_category_assigned_to_user_id = expenses_category_assigned_to_users.id
                WHERE expenses.user_id = :userID AND expenses.date_of_expense BETWEEN :date_begin AND :date_end
                ORDER BY category';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':userID', $userID, PDO::PARAM_INT);
        $stmt->bindValue(':date_begin', $date_begin, PDO::PARAM_STR);
        $stmt->bindValue(':date_end', $date_end, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
