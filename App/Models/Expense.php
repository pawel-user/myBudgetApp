<?php 

namespace App\Models;

use PDO;

use \AllowDynamicProperties;

/**
 * User expense model
 * 
 * PHP version 8.2.6
 */

 #[AllowDynamicProperties]
 class Expense extends \Core\Model {
    /**
     * Error messages
     * 
     * @var array
     */
    public $errors = [];

    /**
     * User expense category names
     * 
     * @var array
     */
    public $expense_names = [];

    /**
     * User payment methods
     * 
     * @var array
     */
    public $payment_methods = [];
    
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
     * Save the user expense model with the current property values
     * 
     * @return void
     */
    public function save()
    {
        $userID = $_SESSION['user_id'];

        $this->validate();

        if (empty($this->errors)) {

            $expenseID = $this->getExpenseCategoryAssignedToUserID($userID)['id'];
            $paymentID = $this->getPaymentMethodAssignedToUserID($userID)['id'];

            $expense_stmt = ExpenseCategory::getUserExpenseCategories($userID);

            foreach ($expense_stmt as $row) {
                $this->expense_names[] = $row['name'];
            }

            $sql = 'INSERT INTO expenses (user_id, expense_category_assigned_to_user_id, payment_method_assigned_to_user_id, amount, date_of_expense, expense_comment)
            VALUES (:user_id, :expense_category_assigned_to_user_id, :payment_method_assigned_to_user_id, :amount, :date_of_expense, :expense_comment)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':user_id', $userID, PDO::PARAM_INT);
            $stmt->bindValue(':expense_category_assigned_to_user_id', $expenseID, PDO::PARAM_INT);
            $stmt->bindValue(':payment_method_assigned_to_user_id', $paymentID, PDO::PARAM_INT);
            $stmt->bindValue(':amount', (float) $this->amount, PDO::PARAM_STR);
            $stmt->bindValue(':date_of_expense', $this->date_of_expense, PDO::PARAM_STR);
            $stmt->bindValue(':expense_comment', $this->expense_comment, PDO::PARAM_STR);

            return $stmt->execute();
        } else {

            return false;
        }
    }

    /**
     * Get user's expense ID by user_id
     * 
     * @return array
     */
    public function getExpenseCategoryAssignedToUserID($userID)
    {
        $sql = 'SELECT (id) FROM expenses_category_assigned_to_users
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
     * Get user's payment method ID by user_id
     * 
     * @return array
     */
    public function getPaymentMethodAssignedToUserID($userID)
    {
        $sql = 'SELECT (id) FROM payment_methods_assigned_to_users
                    WHERE user_id = :user_id 
                    AND name = :name';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $userID, PDO::PARAM_INT);
        $stmt->bindValue(':name', $this->method, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Validate current property values, adding validation error messages to the errors array property for Expense object
     * 
     * @return void
     */
    public function validate()
    {
        // Amount
        if ($this->amount == '') {
            $this->errors[] = 'Amount is required';
        }

        //Expense category
        if ($this->name == '') {
            $this->errors[] = 'Selecting one of the given expense categories is required';
        }

        //Payment methods
        if ($this->method == '') {
            $this->errors[] = 'Selecting one of the given payment methods is required';
        }
    }

    /**
     * Remove user expense item from database
     */
    public static function removeUserExpenseSavedInDatabase($expenseID) {
        $sql = 'DELETE FROM expenses 
                WHERE id = :expenseID';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':expenseID', $expenseID, PDO::PARAM_INT);

        $stmt->execute();
    }
 }