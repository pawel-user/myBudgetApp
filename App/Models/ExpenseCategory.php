<?php

namespace App\Models;

use PDO;

use \App\Flash;
use \App\DataSetup;

/*
* Expense category model
* 
* PHP Version 8.2.6
*/
#[\AllowDynamicProperties]
class ExpenseCategory extends \Core\Model  {

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
     * Edit user expense category in a database
     * 
     * @return boolean
     */
    public static function editExpenseCategory($userID, $categoryID, $newCategoryName)
    {
        if (static::validateCategory($userID, $newCategoryName)) {
            $sql = 'UPDATE expenses_category_assigned_to_users
                    SET name = :newCategory WHERE user_id = :userID AND id = :categoryID
                    LIMIT 1';

            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':userID', $userID, PDO::PARAM_INT);
            $stmt->bindValue(':categoryID', $categoryID, PDO::PARAM_INT);
            $stmt->bindValue(':newCategory', $newCategoryName, PDO::PARAM_STR);

            $stmt->execute();

            return true;
        }
        return false;
    }

    /**
     * Check if exists expense item which is assigned to specific expense category to be deleted
     * 
     * @return boolean
     */
    public static function checkRemoveExpenseCategory($userID, $categoryID) {
        $sql = 'SELECT id FROM expenses 
                WHERE user_id = :userID AND expense_category_assigned_to_user_id = :categoryID';
        
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':userID', $userID, PDO::PARAM_INT);
        $stmt->bindValue(':categoryID', $categoryID, PDO::PARAM_INT);

        $stmt->execute();

        $fetchArray = $stmt->fetch(PDO::FETCH_ASSOC);

        if (empty($fetchArray)) {
            static::removeExpenseCategory($userID, $categoryID);
            return true;
        }             
        return false;
}

    /**
     * Remove user expense category in a database
     * 
     * @return void
     */
    public static function removeExpenseCategory($userID, $categoryID)
    {
        $sql = 'DELETE FROM expenses_category_assigned_to_users
                WHERE user_id = :userID AND id = :categoryID LIMIT 1';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':userID', $userID, PDO::PARAM_INT);
        $stmt->bindValue(':categoryID', $categoryID, PDO::PARAM_INT);

        $stmt->execute();

        //$tableName = 'expenses_category_assigned_to_users';
        //$tableName = implode('', $tableName);
        DataSetup::orderExpenseCategoryTableItems();   
    }

    /**
     * Validate current expense category names
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