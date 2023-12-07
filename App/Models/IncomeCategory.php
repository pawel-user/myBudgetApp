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
     * Download default income categories to array
     * 
     * @return mixed Income object;
     */
    public static function getDefaultIncomeCategories($userID) {

        $sql = 'SELECT id, name FROM incomes_category_default
                WHERE user_id = :userID';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':userID', $userID, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Load default income categories for registered user
     * 
     * @return void
     */
    public static function loadDefaultIncomeCategories($userID) {

        $sql = 'INSERT INTO incomes_category_assigned_to_users (user_id, name)
        SELECT :user_id, name
        FROM incomes_category_default';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $userID, PDO::PARAM_INT);

        $stmt->execute();
    }
}

