<?php

namespace App\Models;

use PDO;
use \App\Models\User;

/*
* Income category model
* 
* PHP Version 8.2.6
*/
#[\AllowDynamicProperties]
class IncomeCategory extends \Core\Model {

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
    public function loadDefaultIncomeCategories() {

        $sql = 'INSERT INTO incomes_category_assigned_to_users (user_id, name)
        SELECT :user_id, name
        FROM incomes_category_default';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $this->id, PDO::PARAM_INT);

        $stmt->execute();
    }
}

