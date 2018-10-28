<?php
/**
 * A lightweight ORM tasked w/ getting/setting values on model records.
 */

namespace orm;

require '../dbconn.php';

class OrmException extends \Exception {};

class AuthException extends OrmException {};

final class ModelRegistry {
    private function __construct() {}
    static $vals = array();
    public static function add($modelName, $modelTableName) {

    }
}

abstract class Model {
    protected static $modelTableName;

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
        // TODO: VALUES should contain `?` symbols i/o actual field _vals
        $query = mysql_escape_string(
            'INSERT INTO ' . static::$modelTableName
            . '(' . join(', ', $fieldnames) . ') '
            . ' VALUES (' . join(', ', $fieldvalues) . ')'
        );
        $conn = establishDatabaseConn();
        if ($res = $conn->query($query)) {
            echo 'Success, see below<br>';
            var_dump($res);
            return $res;  // to improve
        }
        return false;
    }

    /**
     * Would be nice to search for smth which is not a login.
     *
     * @param=login value to search for
     * @return      found login or NULL if there are no users w/ such login
     */
    public static function search($field, $value, $limit=1) {
        $query = (
            'SELECT * FROM ' . 'users'
            . ' WHERE ' . $field . ' = ?'
        );
        if (isset($limit)) {
            $query .= ' LIMIT ' . $limit;
        }
        $rset = array();
        $conn = establishDatabaseConn();
        if ($stmt = $conn->prepare($query)) {
            $typestr = gettype($value)[0];
            $stmt->bind_param($typestr, $value);
            $stmt->execute();
            $res = $stmt->get_result();

            while ($row = $res->fetch_assoc()) {
                $rset[] = new Record(static::$modelTableName, $row);
            }
        }
        return $rset;
    }
}

class Record extends Model {
    public $id;
    private $_vals = array();
    private $_modelTableName;

    function __construct($vals, $modelTableName) {
        $this->id = $vals['id'];
        unset($vals['id']);
        $this->_vals = $vals;
        $this->_modelTableName = $modelTableName;
    }

    public function __set($name, $value) {
        if (empty($this->id) && $name !== 'id') {
            throw \Exception('Attempt to write on a record w/o ID.');
        }
        $query = (
            'UPDATE ' . $this->modelTableName
            . ' SET ' . $name . ' = ' . $value
            . ' WHERE id = ' . $this->id
        );
        $conn = establishDatabaseConn();
        if ($stmt = $conn->prepare($query)) {
            $valueType = gettype($value)[0];
            $stmt->bind_param($valueType, $value);
            $stmt->execute();
        }
    }
    public function readField($name) {
        return $this->_vals[$name];
    }
}
