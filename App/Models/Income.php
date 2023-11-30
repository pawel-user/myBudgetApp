<?php

namespace App\Models;

use PDO;


use \AllowDynamicProperties;

/**
 * User income model
 * 
 * PHP version 8.2
 */

 #[AllowDynamicProperties]
 class Income extends \Core\Model {
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
     * Save the user income model with the current property values
     * 
     * @return void
     */
    public function save() {

        $user_id = $_SESSION['user_id'];

        $income_id = $this->getIncomeCategoryAssignedToUserID($user_id)->id;

        if (empty($this->errors)) {
        
            $sql = 'INSERT INTO incomes (user_id, income_category_assigned_to_user_id, amount, date_of_income, income_comment)
            VALUES (:user_id, :income_category_assigned_to_user_id, :amount, :date_of_income, :income_comment)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindValue(':income_category_assigned_to_user_id', $income_id, PDO::PARAM_INT);
            $stmt->bindValue(':amount', $this->amount, PDO::PARAM_STR);
            $stmt->bindValue(':date_of_income', (float) $this->date_of_income, PDO::PARAM_STR);
            $stmt->bindValue(':income_comment', $this->income_comment, PDO::PARAM_STR);

            return $stmt->execute();

        } else {

            return false;
        }
    }

    /**
     * Get user's income ID by user_id
     * 
     * @return void;
     */
    public function getIncomeCategoryAssignedToUserID($user_id) {
        $sql = 'SELECT (id) FROM incomes_category_assigned_to_users
                    WHERE user_id = :user_id 
                    AND name = :name';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        $stmt->fetch();
    }
 }