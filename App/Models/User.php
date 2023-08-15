<?php

namespace App\Models;

use PDO;
//use PDOException;

/**
 * Post model
 * 
 * PHP version 7.4
 */
#[\AllowDynamicProperties]
class User extends \Core\Model {

    /**
     * Class constructor
     * 
     * @param array $data Initial property values
     * 
     * @return void
     */
    public function __construct($data) {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }

    /**
     * Save the user model with the current property values
     * 
     * @return void
     */
    public function save()
    {
        $password_hash = password_hash($this->password_hash, PASSWORD_DEFAULT);

        $sql = 'INSERT INTO users (username, email, password_hash)
                VALUES (:username, :email, :password_hash)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':username', $this->username, PDO::PARAM_STR);
        $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
        $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);

        $stmt->execute();
    }
}