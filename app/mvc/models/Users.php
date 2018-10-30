<?php
/**
 * Users model.
 *
 * Represents `users` table in a database.
 */

namespace app\models;

class Users {

    private $conn = null;

    public function __construct()
    {
        // TODO: why not? Is that class needed?
        $this->conn = \DatabaseConnection::establishDatabaseConnection();
    }

    /**
     * Try to authenticate using given credentials.
     */
    public function authenticate($login, $pwd)
    {
        if ($this->verifyPwd($login, $pwd)) {
            $_SESSION['login'] = $login;
            // TODO: avoid storing hashes in session, replace w/ remember_token
            $_SESSION['pwd_hash'] = $password_hash;
        }
        throw new \AuthException();
    }

    public function verifyPwd($login, $pwd)
    {
        $sql = "SELECT `password_hash` FROM `users` WHERE `login` = ? LIMIT 1";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param('s', $login);
            $stmt->execute();
            if ($res = $stmt->fetch_assoc()) {
                if (password_verify($pwd, $res['password_hash'])) {
                    return true;
                }
                throw new \AuthException('Such hash mismatch. Wow.');
            }
        }
    }

    public function checkLoginExists($login)
    {
        $query = "SELECT id FROM users WHERE login=? LIMIT 1";
        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param('s', $login);
            $stmt->execute();
            if ($stmt->fetch()) {
                return true;
            }
            return false;
        }
    }

    public function register($login, $pwd)
    {

        // no need to check for existance, DBconn would crash on attempt to
        // create user w/ existing login

        // XXX: dead code
        // if ($this->checkLoginExists($login)) {
        //     throw new \orm\OrmException(
        //         'FATAL: user "' . $login . '" exists already.'
        //     );
        // }
        // end XXX

        $sql = "INSERT INTO `users` (%s) VALUES (%s)";
        $values = [
            'login' => $login,
            'password_hash' => password_hash($pwd, PASSWORD_DEFAULT),
        ];

        // compile statement - to be extracted, this is highly reusable
        $fieldNames = $fieldVals = $fieldMarkers = $types = [];
        foreach ($values as $fieldName => $fieldVal) {
            $fieldNames[] = $fieldName;
            $fieldMarkers[] = '?';
            $fieldVals[] = $fieldVal;
            // 'd' for doubles, 'i' for ints, 's' for the rest
            $types[] = is_int($fieldVal) ? 'i' : is_double($fieldVal) ? 'd' : 's';
        }
        $sql = sprintf(
            $sql, implode(', ', $fieldNames), implode(', ', $fieldMarkers)
        );
        $stmt = $this->conn->prepare($sql);
        call_user_func_array(
            [$stmt, 'bind_param'], array_merge([implode($types)], $fieldVals)
        );
        // end compile statement

        $stmt->execute();
        if ($stmt->error) {
            throw new \MyException('Unable to create user: ' . $stmt->error);
        }
    }
}
