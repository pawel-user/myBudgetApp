<?php

namespace App\Models;

use PDO;

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

}