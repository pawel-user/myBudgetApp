<?php

namespace App;

use PDO;

/**
 * Setting up data in the database
 * 
 * PHP version 8.2.6 */

 class DataSetup extends \Core\Model {
    /**
     * Ordering of data identifiers and setting auto-incrementing 'expenses_category_assigned_to_users' table items from database
     * 
     * @return void
     */

    public static function orderExpenseCategoryTableItems() {
        $sql = 'SELECT * FROM expenses_category_assigned_to_users ORDER BY id;
        SET @count = 0;
        UPDATE expenses_category_assigned_to_users SET id = @count:= @count + 1;
        ALTER TABLE expenses_category_assigned_to_users AUTO_INCREMENT = 1';

    $db = static::getDB();
    $stmt = $db->prepare($sql);

    $stmt->execute();
    }

    /*public static function orderTableItems($tableName) {
        $sql = "SELECT * FROM '" . $tableName . "' SET @count = 0;
        UPDATE '" . $tableName . "' SET id = @count:= @count + 1;
        ALTER TABLE '" . $tableName . "' AUTO_INCREMENT = 1";

    $db = static::getDB();
    $stmt = $db->prepare($sql);
    $stmt->execute();
    }*/

    /*public static function orderTableItems() {
        $sql = 'SELECT * FROM :tableName ORDER BY id;
        SET @count = 0;
        UPDATE :tableName  SET id = @count:= @count + 1;
        ALTER TABLE :tableName AUTO_INCREMENT = 1';

    $db = static::getDB();
    $stmt->bindValue(':tableName', $tableName, PDO::PARAM_STR);
    //$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
    $stmt = $db->prepare($sql);

    $stmt->execute();
    }*/

    /**
     * Ordering of data identifiers and setting auto-incrementing 'incomes_category_assigned_to_users' table items from database
     * 
     * @return void
     */

     public static function orderIncomeCategoryTableItems() {
        $sql = 'SELECT * FROM incomes_category_assigned_to_users ORDER BY id;
        SET @count = 0;
        UPDATE incomes_category_assigned_to_users SET id = @count:= @count + 1;
        ALTER TABLE incomes_category_assigned_to_users AUTO_INCREMENT = 1';

    $db = static::getDB();
    $stmt = $db->prepare($sql);

    $stmt->execute();
    }

    /**
     * Ordering of data identifiers and setting auto-incrementing 'payment_methods_assigned_to_users' table items from database
     * 
     * @return void
     */

     public static function orderPaymentMethodsTableItems() {
        $sql = 'SELECT * FROM payment_methods_assigned_to_users ORDER BY id;
        SET @count = 0;
        UPDATE payment_methods_assigned_to_users SET id = @count:= @count + 1;
        ALTER TABLE payment_methods_assigned_to_users AUTO_INCREMENT = 1';

    $db = static::getDB();
    $stmt = $db->prepare($sql);

    $stmt->execute();
    }
 }