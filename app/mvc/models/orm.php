<?php
/**
 * A lightweight ORM tasked w/ getting/setting values on various model records.
 */

namespace orm;

require '../dbconn.php';

class OrmException extends \Exception {};

class RecordNotFoundException extends OrmException {};
class AuthException extends OrmException {};

abstract class Model {
    protected static
        $conn,
        $dbTable,
        $pk = 'id';
    private
        $_loadData,
        $_loadMethod,
        $_isNew = false,
        $_modifiedFields = array();

    const
        LOAD_BY_PK = 1,
        LOAD_BY_ARRAY = 2,
        LOAD_NEW = 3,
        LOAD_EMPTY = 4;

    function __construct($data = null, $method = self::LOAD_EMPTY) {
        $this->_loadData = $data;

        switch($method) {
            case self::LOAD_BY_PK:
                $this->loadByPk();
                break;
            case self::LOAD_BY_ARRAY:
                $this->loadByArray();
                break;
            case self::LOAD_NEW:
                $this->loadByArray();
                $this->insert();
                break;
            case self::LOAD_EMPTY:
                $this->hydrateEmpty();
                break;
        }

        $this->initialise();
    }

    // DB info
    public static function getTableName() {
        $callerClass = get_called_class();
        if (isset($callerClass::$dbTable)) {
            return $callerClass::$dbTable;
        }
        return strtolower($callerClass);
    }
    private function getColumnNames() {
        $conn = self::getConnection();
        $result = $conn->query(sprintf('DESCRIBE %s', self::getTableName()));
        if ($result === false) {
            throw new OrmException(
                sprintf('Unable to fetch column names. %s', $conn->error)
            );
        }
        $ret = array();
        while ($row = $result->fetch_assoc()) {
            $ret[] = $row['Field'];
        }
        $result->close();
        return $ret;
    }

    private function hydrateEmpty() {
        foreach ($this->getColumnNames() as $field) {
            $this->{$field} = null;
        }
    }

    public function useConnection(mysqli $conn) {
        if($conn === null) {
            $conn = establishDatabaseConn();
        }
        self::$conn = $conn;
    }
    public function getConnection() {
        return self::$conn;
    }
    public function getPk() {
        return self::$pk;
    }

    private function parseValueType($val) {
        if (is_int($val)) {
            return 'i';
        }
        if (is_double($val)) {
            return 'd';
        }
        return 's';
    }

    const FETCH_ONE = 1;
    const FETCH_MULTIPLE = 2;
    const FETCH_NONE = 3;

    private function execute($sql, $return = Model::FETCH_MANY) {
        $result = self::getConnection()->query($sql);
        if (!$result) {
            throw new OrmException(
                sprintf('Unable to execute SQL statement. %s'),
                self::getConnection()->error
            );
        }
        if ($return === Model::FETCH_NONE) {
            return;
        }
        $ret = array();
        while ($row = $result->fetch_assoc()) {
            $ret[] = $row;
        }
        $result->close();
        if ($return === Model::FETCH_ONE) {
            $ret = isset($ret[0])? $ret[0] : null;
        }
        return $ret;
    }

    public function create($valuesMapping) {
        $fieldNames = $fieldMarkers = $types = $values = array();

        foreach ($valuesMapping as $fieldName => $fieldValue) {
            $fieldNames[] = $fieldName;
            $fieldMarkers[] = '?';
            $types[] = $this->parseValueType($fieldValue);
            $values[] = &$valuesMapping[$fieldName];
        }

        $query = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            static::dbTable, join(', ', $fieldNames), join(', ', $fieldMarkers)
        );
        $stmt = self.getConnection()->prepare($query);
        if (!$stmt) {
            throw new \Exception(self::getConnection()->error."\n\n".$query);
        }
        call_user_func_array(
            array($stmt, 'bind_param'),
            array_merge(array(implode($types)), $values)
        );
    }


    public function id() {
        return $this->{self::getPk()};
    }
    public function save() {
        if ($this->isNew) {
            $this->insert();
        } else {
            $this->update();
        }
    }
    public function update() {
        if ($this->_isNew) {
            throw new OrmException('Unable to UPDATE, record is new.')
        }
        $pk = self::getPk();
        $id = $this->id();

        $
    }



    /**
     * Look for records w/ `field` set to `value`.
     *
     * @param  field     value to search for
     * @return Recordset login or NULL if there are no users w/ such login
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
    protected static $dbTable;

    public function __set($name, $value) {
        if (empty($this->id) && $name !== 'id') {
            throw new \Exception('Attempt to write on a record w/o ID.');
        }
        $query = (
            'UPDATE ' . $this->dbTable
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
