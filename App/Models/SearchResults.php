<?php

namespace App\Models;

use PDO;

use \AllowDynamicProperties;

/**
 * Table of results for user income and expense records
 * 
 * PHP version 8.2.6
 */
#[AllowDynamicProperties]
class SearchResults extends \Core\Model
{
    /**
     * Search income results for search iterm
     */
    public $search_income_data = [];


    /**
     * Search expense results for search iterm
     */
    public $search_expense_data = [];

    /**
     * Number of income results
     */
    public $income_counter;

    /**
     * Number of expense results
     */
    public $expense_counter;
    
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
     * Get user's results for search term
     * 
     * @return array
     */
    public static function searchIncomeTermsFromDatabase($userID, $keyword)
    {
        $sql = 'SELECT 
                    incomes_category_assigned_to_users.name,
                    incomes.amount,
                    incomes.date_of_income,
                    incomes.income_comment
                FROM incomes_category_assigned_to_users
                    INNER JOIN incomes ON incomes.income_category_assigned_to_user_id = incomes_category_assigned_to_users.id
                WHERE incomes.user_id = :userID AND
                    (incomes_category_assigned_to_users.name LIKE :keyword OR
                    incomes.amount LIKE :keyword OR
                    incomes.date_of_income LIKE :keyword OR
                    incomes.income_comment LIKE :keyword)
                ORDER BY incomes_category_assigned_to_users.name DESC';

        $db = static::getDB();
        $query = $db->prepare($sql);
        $query->bindValue(':userID', $userID, PDO::PARAM_INT);
        $query->bindValue(':keyword', '%' . $keyword . '%', PDO::PARAM_STR);

        $query->execute();

        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $rows = $query->rowCount();

        //$set_results = [$results, $rows];

        //var_dump($results);
        //var_dump($rows);
        //exit;

        return [$results, $rows];
    }

    /**
     * Get user's results for search term
     * 
     * @return array
     */
    public static function searchExpenseTermsFromDatabase($userID, $keyword)
    {
        $sql = 'SELECT 
                    expenses_category_assigned_to_users.name,
                    expenses.amount,
                    expenses.date_of_expense,
                    expenses.expense_comment
                FROM expenses_category_assigned_to_users
                    INNER JOIN expenses ON expenses.expense_category_assigned_to_user_id = expenses_category_assigned_to_users.id
                WHERE expenses.user_id = :userID AND
                    (expenses_category_assigned_to_users.name LIKE :keyword OR
                    expenses.amount LIKE :keyword OR
                    expenses.date_of_expense LIKE :keyword OR
                    expenses.expense_comment LIKE :keyword)
                ORDER BY expenses_category_assigned_to_users.name  DESC';

        $db = static::getDB();
        $query = $db->prepare($sql);
        $query->bindValue(':userID', $userID, PDO::PARAM_INT);
        $query->bindValue(':keyword', '%' . $keyword . '%', PDO::PARAM_STR);

        $query->execute();

        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $rows = $query->rowCount();

        //var_dump($results);
        //var_dump($rows);
        //exit;

        return [$results, $rows];
    }
}