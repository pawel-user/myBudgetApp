<?php

namespace App\Models;

use PDO;
use \App\DataSetup;

use \AllowDynamicProperties;

/**
 * User income model
 * 
 * PHP version 8.2.6
 */

#[AllowDynamicProperties]
class Income extends \Core\Model
{
    /**
     * Error messages
     * 
     * @var array
     */
    public $errors = [];

    /**
     * User income category names
     * 
     * @var array
     */
    public $income_names = [];

    /**
     * Action name
     * 
     * @var string
     */
    public $action = '';


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
    public function save()
    {
        $userID = $_SESSION['user_id'];

        $this->validate();

        if (empty($this->errors)) {

            $incomeID = $this->getIncomeCategoryAssignedToUserID($userID)['id'];

            $income_stmt = IncomeCategory::getUserIncomeCategories($userID);

            foreach ($income_stmt as $row) {
                $this->income_names[] = $row['name'];
            }

            $sql = 'INSERT INTO incomes (user_id, income_category_assigned_to_user_id, amount, date_of_income, income_comment)
            VALUES (:user_id, :income_category_assigned_to_user_id, :amount, :date_of_income, :income_comment)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':user_id', $userID, PDO::PARAM_INT);
            $stmt->bindValue(':income_category_assigned_to_user_id', $incomeID, PDO::PARAM_INT);
            $stmt->bindValue(':amount', (float) $this->amount, PDO::PARAM_STR);
            $stmt->bindValue(':date_of_income', $this->date_of_income, PDO::PARAM_STR);
            $stmt->bindValue(':income_comment', $this->income_comment, PDO::PARAM_STR);

            return $stmt->execute();
        } else {

            return false;
        }
    }

    /**
     * Get user's income ID by user_id
     * 
     * @return array
     */
    public function getIncomeCategoryAssignedToUserID($userID)
    {
        $sql = 'SELECT (id) FROM incomes_category_assigned_to_users
                    WHERE user_id = :user_id 
                    AND name = :name';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $userID, PDO::PARAM_INT);
        $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get user's income ID by income category name
     * 
     * @return array
     */
    public static function getIncomeIdAssignedToUser($userID, $incomeCategory)
    {
        $sql = 'SELECT (id) FROM incomes_category_assigned_to_users
                    WHERE user_id = :userID 
                    AND name = :incomeCategory';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':userID', $userID, PDO::PARAM_INT);
        $stmt->bindValue(':incomeCategory', $incomeCategory, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Validate current property values, adding validation error messages to the errors array property for Income object
     * 
     * @return void
     */
    public function validate()
    {
        // Amount
        if ($this->amount == '') {
            $this->errors[] = 'Amount is required';
        }

        //Income category
        if ($this->name == '') {
            $this->errors[] = 'Selecting one of the given income categories is required';
        }
    }

    /**
     * Get user's income names by name
     * 
     * @return void;
     */

    /*public static function loadUserIncomeNames($userID)
    {
        $sql = 'SELECT (name) FROM incomes_category_assigned_to_users
                WHERE user_id = :user_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $userID, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        $stmt->fetch();
    }*/

    /**
     * Remove user income item from database
     */
    public static function removeUserIncomeSavedInDatabase($incomeID) {
        $sql = 'DELETE FROM incomes 
                WHERE id = :incomeID';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':incomeID', $incomeID, PDO::PARAM_INT);

        $stmt->execute();

        DataSetup::orderIncomeTableItems(); 
        //$tableName = "incomes";
        //DataSetup::orderTableItems($tableName);
    }

    /**
     * Update user income item in database
     * 
     * @return void
     */
    public static function editUserIncomeSavedInDatabase($incomeID, $incomeAmount, $incomeDate, $incomeComment, $incomeCategoryAssignedToUsersID) {

        $sql = 'UPDATE incomes
                    SET incomes.amount = :incomeAmount, incomes.date_of_income = :incomeDate, incomes.income_comment = :incomeComment, incomes.income_category_assigned_to_user_id = :incomeCategoryAssignedToUsersID
                    WHERE incomes.id = :incomeID';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':incomeAmount', $incomeAmount, PDO::PARAM_STR);
        $stmt->bindValue(':incomeDate', $incomeDate, PDO::PARAM_STR);
        $stmt->bindValue(':incomeComment', $incomeComment, PDO::PARAM_STR);
        $stmt->bindValue(':incomeID', $incomeID, PDO::PARAM_INT);
        $stmt->bindValue(':incomeCategoryAssignedToUsersID', $incomeCategoryAssignedToUsersID, PDO::PARAM_INT);

        $stmt->execute();
    }
}
