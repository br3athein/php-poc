<?php
/**
 * A lightweight ORM tasked w/ getting/setting values on various model records.
 */

namespace orm;

require '../dbconn.php';

class OrmException extends \Exception {};

class RecordNotFoundException extends OrmException {};
class AuthException extends OrmException {};

final class ModelRegistry {
    private function __construct() {}
    static $vals = array();
    public static function add($modelName, $modelTableName) {

    }
}

abstract class Model {
    protected static $conn, $modelTableName;

    public function useConnection(mysqli $conn) {
        if($conn === null) {
            $conn = establishDatabaseConn();
        }
        self::$conn = $conn;
    }
    public function getConnection() {
        return self::$conn;
    }

    // do we need this?
    private function _getModel() {
        // look up for the particular class
        foreach(get_declared_classes() as $class) {
            static::$modelTableName;
        }
    }

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
        if ($res = self::getConnection()->query($query)) {
            return $res;
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

            var_dump($res);
            while ($row = $res->fetch_assoc()) {
                // refer to caller model directly
                $model = get_called_class();
                $rset[] = new $model($row);
            }
        }
        return $rset;
    }
    public static function readField(Record $record, $name) {
        return $record->_vals[$name];
    }
}

class Record extends Model {
    public $id;
    private $_vals = array();
    private $_modelTableName;

    function __construct($vals) {
        $this->id = $vals['id'];
        unset($vals['id']);
        $this->_vals = $vals;
    }

    public function __set($name, $value) {
        if (empty($this->id) && $name !== 'id') {
            throw new \Exception('Attempt to write on a record w/o ID.');
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
}
