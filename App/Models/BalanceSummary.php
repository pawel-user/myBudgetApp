<?php

namespace App\Models;

use PDO;

use \AllowDynamicProperties;

/**
 * User incomes & expenses table
 * 
 * PHP version 8.2.6
 */
#[AllowDynamicProperties]
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
     * Total amount for each income category in selected period
     */
    public $income_category_total_amount = [];

    /**
     * Total amount for each expense category in selected period
     */
    public $expense_category_total_amount = [];

    /**
     * Total sum of income amounts in selected period
     */
    public $total_incomes;

    /**
     * Total sum of expense amounts in selected period
     */
    public $total_expenses;

    /**
     * Final balance of incomes and expenses in selected period
     */
    public $final_balance;

    /**
     * Current month and year
     */
    public $current_month_and_year;

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
    public static function getAllUserIncomesForSelectedPeriod($userID, $date_begin, $date_end)
    {
        $sql = 'SELECT 
                    incomes_category_assigned_to_users.name AS category,
                    incomes.amount AS amount,
                    incomes.date_of_income,
                    incomes.income_comment
                FROM incomes_category_assigned_to_users
                    INNER JOIN incomes ON incomes.income_category_assigned_to_user_id = incomes_category_assigned_to_users.id
                WHERE incomes.user_id = :userID AND incomes.date_of_income BETWEEN :date_begin AND :date_end
                ORDER BY incomes.date_of_income DESC';

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
    public static function getAllUserExpensesForSelectedPeriod($userID, $date_begin, $date_end)
    {
        $sql = 'SELECT 
                    expenses_category_assigned_to_users.name AS category,
                    expenses.amount AS amount,
                    expenses.date_of_expense,
                    expenses.expense_comment AS comment
                FROM expenses_category_assigned_to_users
                    INNER JOIN expenses ON expenses.expense_category_assigned_to_user_id = expenses_category_assigned_to_users.id
                WHERE expenses.user_id = :userID AND expenses.date_of_expense BETWEEN :date_begin AND :date_end
                ORDER BY expenses.date_of_expense DESC';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':userID', $userID, PDO::PARAM_INT);
        $stmt->bindValue(':date_begin', $date_begin, PDO::PARAM_STR);
        $stmt->bindValue(':date_end', $date_end, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get user incomes with total amount for all the same income categories and selected period of time
     * 
     * @return array
     */
    public static function getTotalAmountOfIncomesForEachCategoryInSelectedPeriod($userID, $date_begin, $date_end) {
        $sql = 'SELECT  
                    incomes_category_assigned_to_users.name AS category, 
                    SUM(incomes.amount) AS amount
                FROM incomes_category_assigned_to_users
                    INNER JOIN incomes ON incomes.income_category_assigned_to_user_id = incomes_category_assigned_to_users.id
                WHERE incomes.user_id = :userID AND incomes.date_of_income BETWEEN :date_begin AND :date_end
                GROUP BY incomes.income_category_assigned_to_user_id
                ORDER BY category ASC';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':userID', $userID, PDO::PARAM_INT);
        $stmt->bindValue(':date_begin', $date_begin, PDO::PARAM_STR);
        $stmt->bindValue(':date_end', $date_end, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get user expenses with total amount for all the same income categories and selected period of time
     * 
     * @return array
     */
    public static function getTotalAmountOfExpensesForEachCategoryInSelectedPeriod($userID, $date_begin, $date_end) {
        $sql = 'SELECT  
                    expenses_category_assigned_to_users.name AS category, 
                    SUM(expenses.amount) AS amount
                FROM expenses_category_assigned_to_users
                    INNER JOIN expenses ON expenses.expense_category_assigned_to_user_id = expenses_category_assigned_to_users.id
                WHERE expenses.user_id = :userID AND expenses.date_of_expense BETWEEN :date_begin AND :date_end
                GROUP BY expenses.expense_category_assigned_to_user_id
                ORDER BY category ASC';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':userID', $userID, PDO::PARAM_INT);
        $stmt->bindValue(':date_begin', $date_begin, PDO::PARAM_STR);
        $stmt->bindValue(':date_end', $date_end, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get total sum of user income amounts in selected period of time
     * 
     * @return array
     */
    public static function getTotalSumOfIncomesInSelectedPeriod($userID, $date_begin, $date_end) {
        $sql = 'SELECT  
                    SUM(incomes.amount) AS amount
                FROM incomes
                WHERE incomes.user_id = :userID AND incomes.date_of_income BETWEEN :date_begin AND :date_end';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':userID', $userID, PDO::PARAM_INT);
        $stmt->bindValue(':date_begin', $date_begin, PDO::PARAM_STR);
        $stmt->bindValue(':date_end', $date_end, PDO::PARAM_STR);

        //$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        //$stmt->execute();
        //return $stmt->fetchAll(PDO::FETCH_CLASS);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get total sum of user expense amounts in selected period of time
     * 
     * @return array
     */
    public static function getTotalSumOfExpensesInSelectedPeriod($userID, $date_begin, $date_end) {
        $sql = 'SELECT  
                    SUM(expenses.amount) AS amount
                FROM expenses
                WHERE expenses.user_id = :userID AND expenses.date_of_expense BETWEEN :date_begin AND :date_end';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':userID', $userID, PDO::PARAM_INT);
        $stmt->bindValue(':date_begin', $date_begin, PDO::PARAM_STR);
        $stmt->bindValue(':date_end', $date_end, PDO::PARAM_STR);

        //$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        //$stmt->execute();
        //return $stmt->fetchAll(PDO::FETCH_CLASS);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
