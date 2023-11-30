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
     * Income category names
     * 
     * @var array
     */
    public $income_names = [];

     /**
     * Class constructor
     * 
     * @param array $data Load all default category names to array and initial property values
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
    public function downloadDefaultIncomeCategories() {

        $sql = 'SELECT *
        FROM incomes_category_default';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_NUM);

        $stmt->execute();

        $stmt->fetch();

        $index = 0;

        foreach ($stmt as $row) {
            $income_names[$index] = $row[1];
            $index++;
        }

        return $income_names;
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

