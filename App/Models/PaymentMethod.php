<?php

namespace App\Models;

use PDO;

use App\Flash;

/*
* Payment category model
* 
* PHP Version 8.2.6
*/

#[\AllowDynamicProperties]
class PaymentMethod extends \Core\Model
{
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
     * Load default payment methods for registered user
     * 
     * @return void
     */
    public static function loadDefaultPaymentMethods($userID)
    {

        $sql = 'INSERT INTO payment_methods_assigned_to_users (user_id, name)
                SELECT :userID, name
                FROM payment_methods_default';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':userID', $userID, PDO::PARAM_INT);

        $stmt->execute();
    }

    /**
     * Get user payment methods to array
     * 
     * @return array
     */
    public static function getUserPaymentMethods($userID)
    {

        $sql = 'SELECT id, name FROM payment_methods_assigned_to_users
                WHERE user_id = :userID';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':userID', $userID, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * Create new user payment method and add to database
     * 
     * @return boolean
     */
    public static function createPaymentMethod($userID, $paymentMethod)
    {

        if (static::validateMethod($userID, $paymentMethod)) {
            $sql = 'INSERT INTO payment_methods_assigned_to_users (user_id, name) 
                    VALUES (:userID, :name)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':userID', $userID, PDO::PARAM_INT);
            $stmt->bindValue(':name', $paymentMethod, PDO::PARAM_STR);

            $stmt->execute();

            return true;
        }

        return false;
    }

    /**
     * Edit user payment method in a database
     * 
     * @return boolean
     */
    public static function editPaymentMethod($userID, $paymentID, $newPaymentMethod)
    {
        if (static::validateMethod($userID, $newPaymentMethod)) {
            $sql = 'UPDATE payment_methods_assigned_to_users
                    SET name = :newMethod WHERE user_id = :userID AND id = :paymentID
                    LIMIT 1';

            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':userID', $userID, PDO::PARAM_INT);
            $stmt->bindValue(':paymentID', $paymentID, PDO::PARAM_INT);
            $stmt->bindValue(':newMethod', $newPaymentMethod, PDO::PARAM_STR);

            $stmt->execute();

            return true;
        }
        return false;
    }

    /**
     * Remove user payment method in a database
     * 
     * @return void
     */
    public static function removePaymentMethod($userID, $paymentID)
    {
        $sql = 'DELETE FROM payment_methods_assigned_to_users
                WHERE user_id = :userID AND id = :paymentID LIMIT 1';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':userID', $userID, PDO::PARAM_INT);
        $stmt->bindValue(':paymentID', $paymentID, PDO::PARAM_INT);

        $stmt->execute();
    }


    /**
     * Validate current payment method names
     * 
     * @return void
     */
    private static function validateMethod($userID, $paymentMethod)
    {
        $paymentMethodToUpper = strtoupper($paymentMethod);

        //$pattern = '/[^\wa-zA-Z0-9, ]/i';
        //$pattern = '/[^\p{L}\d][^\p{L}]+/i';
        //$pattern = '/[^\p{L}]+/i';
        $pattern = '/^[a-zA-Z][a-zA-Z0-9 ,]+$/';
        $result = preg_match($pattern, $paymentMethodToUpper);
        //var_dump($result);
        //exit;

        if ($result == 0) {
            Flash::addMessage('Forbidden characters were used for the payment method name.', Flash::DANGER);
            return false;
        }

        if (static::checkExistenceMethod($userID, $paymentMethodToUpper)) {
            Flash::addMessage('The entered payment method already exists.', Flash::WARNING);
            return false;
        }
        return true;
    }

    /**
     * Check if new adding or editing payment method name already exists 
     * 
     * @return boolean
     */
    private static function checkExistenceMethod($userID, $methodToUpper)
    {

        $sql = 'SELECT UPPER(name) AS name FROM payment_methods_assigned_to_users
                WHERE user_id = :userID AND name = :method';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':userID', $userID, PDO::PARAM_INT);
        $stmt->bindValue(':method', $methodToUpper, PDO::PARAM_STR);

        $stmt->execute();

        if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
            return false;
        }
        return true;
    }
}
