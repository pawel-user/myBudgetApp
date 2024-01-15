<?php

namespace App\Models;

use PDO;

/*
* Payment category model
* 
* PHP Version 8.2.6
*/
#[\AllowDynamicProperties]
class PaymentCategory extends \Core\Model {
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
    public static function loadDefaultPaymentMethods($userID) {

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
    public static function getUserPaymentMethods($userID) {

        $sql = 'SELECT id, name FROM payment_methods_assigned_to_users
                WHERE user_id = :userID';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':userID', $userID, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}