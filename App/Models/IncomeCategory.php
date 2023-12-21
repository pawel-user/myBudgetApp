<?php

namespace App\Models;

use PDO;

/*
* Income category model
* 
* PHP Version 8.2.6
*/
#[\AllowDynamicProperties]
class IncomeCategory extends \Core\Model {

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
     * Load default income categories for registered user
     * 
     * @return void
     */
    public static function loadDefaultIncomeCategories($userID) {

        $sql = 'INSERT INTO incomes_category_assigned_to_users (user_id, name)
        SELECT :userID, name
        FROM incomes_category_default';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':userID', $userID, PDO::PARAM_INT);

        $stmt->execute();
    }

    /**
     * Get user income categories to array
     * 
     * @return array
     */
    public static function getUserIncomeCategories($userID) {

        $sql = 'SELECT id, name FROM incomes_category_assigned_to_users
                WHERE user_id = :userID';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':userID', $userID, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Add new user income category to database
     * 
     * @return void
     */
    public static function addIncomeCategory($userID, $incomeCategory) {

    }

    /**
     * Validate current income category names
     * 
     * @return void
     */
    private static function validateCategory($userID, $incomeCategory) {

    }

    /**
     * Check if new adding or editing income category name already exists 
     * 
     * @return boolean
     */
    private static function checkExistenceCategory($userID, $categoryToUpper) {

        //$sql = 'SELECT name FROM (SELECT UPPER(name) AS name FROM incomes_category_assigned_to_users
                //WHERE user_id = :userID) AND name = :category';

        $sql = 'SELECT UPPER(name) AS name FROM incomes_category_assigned_to_users
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