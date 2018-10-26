<?php
/**
 * A lightweight ORM tasked w/ getting/setting values on model records.
 */

require '../dbconn.php';

class Model {
    public $Id;
    private $_modelTableName;

    public function create($valuesMapping) {
        $fieldnames = [];
        $fieldvalues = [];
        $typestring = '';

        // not the best usage for associative arrays, probably
        foreach ($valuesMapping as $fieldName => $fieldValue) {
            array_push($fieldnames, $fieldName);
            array_push($fieldvalues, $fieldValue);
            $typestring .= gettype($fieldValue)[0];
        }
        // TODO: VALUES should contain `?` symbols i/o actual field vals
        $query = mysql_escape_string(
            'INSERT INTO ' . $_modelTableName
            . '(' . join(', ', $fieldnames) . ') '
            . ' VALUES (' . join(', ', $fieldvalues) . ')'
        );
        $conn = establishDatabaseConn();
        $conn->query($query);
    }

    public function __set($name, $value) {
        if (empty($this->Id)) {
            throw Exception('Attempt to write on a record w/o ID.');
        }
        $query = (
            'UPDATE ' . $this->_modelTableName
            . ' SET ' . $name . ' = ' . $value
            . ' WHERE id = ' . $this->Id
        );
        $conn = establishDatabaseConn();
        if ($stmt = $conn->prepare($query)) {
            $valueType = gettype($value)[0];
            $stmt->bind_param($valueType, $value);
            $stmt->execute();
        }
    }

    public static function search($field, $value, $limit=1) {
        $query_raw = (
            'SELECT * FROM ' . $this->_modelTableName
            . ' WHERE ' . $field . ' = ' . $value
        );
        if (isset($limit)) {
            $query_raw .= ' LIMIT ' . $limit;
        }
        $query = mysql_escape_string($query_raw);
        $conn = establishDatabaseConn();
        $conn->query($query);
    }
}
