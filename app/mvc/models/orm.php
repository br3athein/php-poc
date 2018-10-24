<?php
/**
 * An ultra-lightweight ORM to get/set values on model records.
 */

require '/app/mvc/dbconn.php';

class Model {
    public $Id;
    private $_modelTableName;

    public function __set($name, $value) {
        if (empty(this.Id)) {
            throw Exception('Attempt to write on a record w/o ID.');
        }
        $conn = establishDatabaseConn();
        $query = (
            'INSERT INTO ' . this._modelTableName
            . '(' . $name . ') VALUES (?)'
        );
        if ($stmt = $conn->prepare($query)) {
            $valueType = gettype($value)[0];
            $stmt->bind_param($valueType, $value);
            $stmt->execute();
        }
    }
}
