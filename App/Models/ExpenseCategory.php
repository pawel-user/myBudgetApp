<?php

namespace App\Models;

use PDO;

use \App\Flash;

/*
* Expense category model
* 
* PHP Version 8.2.6
*/
#[\AllowDynamicProperties]
class ExpenseCategory extends \Core\Model {

    /**
     * Error messages
     * 
     * @var array
     */
    public $errors = [];

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
     * Load default expense categories for registered user
     * 
     * @return void
     */
    public static function loadDefaultExpenseCategories($userID) {

        $sql = 'INSERT INTO expenses_category_assigned_to_users (user_id, name)
                SELECT :userID, name
                FROM expenses_category_default';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':userID', $userID, PDO::PARAM_INT);

        $stmt->execute();
    }

    /**
     * Get user expense categories to array
     * 
     * @return array
     */
    public static function getUserExpenseCategories($userID) {

        $sql = 'SELECT id, name FROM expenses_category_assigned_to_users
                WHERE user_id = :userID';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':userID', $userID, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * Create new user expense category and add to database
     * 
     * @return boolean
     */
    public static function createExpenseCategory($userID, $expenseCategory) {

        if (static::validateCategory($userID, $expenseCategory)) {
            $sql = 'INSERT INTO expenses_category_assigned_to_users (user_id, name) 
                    VALUES (:userID, :name)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':userID', $userID, PDO::PARAM_INT);
            $stmt->bindValue(':name', $expenseCategory, PDO::PARAM_STR);

            $stmt->execute();

            return true;
        }

        return false;
    }

    /**
     * Validate current income category names
     * 
     * @return void
     */
    private static function validateCategory($userID, $expenseCategory) {
        $categoryToUpper = strtoupper($expenseCategory);

        //$pattern = '/[^\wa-zA-Z0-9, ]/i';
        $pattern = '/^[a-zA-Z][a-zA-Z0-9 ,]+$/';
        $result = preg_match($pattern, $categoryToUpper);

        if ($result == 0) {
            Flash::addMessage('Forbidden characters were used for the category name.', Flash::DANGER);
            return false;
        }

        if (static::checkExistenceCategory($userID, $categoryToUpper)) {
            Flash::addMessage('The entered category already exists.', Flash::WARNING);
            return false;
        }
        return true;
    }

    /**
     * Check if new adding or editing expense category name already exists 
     * 
     * @return boolean
     */
    private static function checkExistenceCategory($userID, $categoryToUpper) {

        $sql = 'SELECT UPPER(name) AS name FROM expenses_category_assigned_to_users
                WHERE user_id = :userID AND name = :category';
        
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':userID', $userID, PDO::PARAM_INT);
        $stmt->bindValue(':category', $categoryToUpper, PDO::PARAM_STR);

        $stmt->execute();

        if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
            return false;
        }
        return true;
    }
}