<?php
/**
 * Users model.
 *
 * Represents `users` table in a database.
 */
require '/app/mvc/models/orm.php';

class AuthException extends OrmException {};

class Users extends \orm\Model {

    public $IsBanned;

    protected static $modelTableName = 'users';

    /**
     * Try to authenticate using given credentials.
     */
    public function authenticate($pwd) {
        if (!$this) {
            throw AuthException('Username not found, noone to authenticate.');
        }
        if (password_verify($pwd, $this->readField('password_hash'))) {
            session_start();
            $_SESSION['login'] = $username;
            $_SESSION['pwd_hash'] = $this->readField('password_hash');
            return true;
        }
        throw AuthException(
            'login failed for ' . $username
            . 'Try another pwd, lol.'
        );
    }

    public function searchByLogin($login) {
        $conn = establishDatabaseConn();
        $query = 'SELECT login FROM users WHERE login = ? LIMIT 1';
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param('s', $login);
            $stmt->execute();
            $stmt->bind_result($existingLogin);
            $stmt->fetch();
            return $existingLogin;
        }
        return null;
    }

    public function register($login, $pwd) {
        if (Users.search('login', $login)) {
            throw Exception('FATAL: user "' . $login . '" exists already.');
        }

        $query = 'INSERT INTO users (login, password_hash) VALUES (?, ?)';
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param(
                'ss',
                $requested_login,
                $hashed_pwd
            );
            if ($stmt->execute() === true) {header('Location: index.htm');
                die();
            } else {
                echo 'user ' . $requested_login . ' wasn\'t successfully created.';
            }
        }
    }
}
