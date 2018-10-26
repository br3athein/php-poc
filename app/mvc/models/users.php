<?php
/**
 * Users model.
 *
 * Represents `users` table in a database.
 */
require '/app/mvc/models/orm.php';

class Users extends Model {
    public $Login;
    public $PwdHash;
    public $IsBanned;

    private $_classTableName = 'users';

    /**
     * Try to authenticate using given credentials.
     */
    public function authenticate($username, $pwd) {
        $conn = establishDatabaseConn();
        $query = ('SELECT login, password_hash ' .
                  'FROM users WHERE login = ? ' .
                  'LIMIT 1');
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param('s', $searchLogin);
            $stmt->execute();
            $stmt->bind_result($user_login, $user_pwd_hash);
            $stmt->fetch();

            if ($user_login && password_verify($search_pwd, $user_pwd_hash)) {
                session_start();
                $_SESSION['login'] = $searchLogin;
                $_SESSION['pwd_hash'] = $user_pwd_hash;
                header('Location: /?action=my');
                die();
            } else {
                throw Exception(
                    'Login failed for ' . $searchLogin .
                    ': wrong combination of credentials.'
                );
            }
        }
    }

    public function search($login) {
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

    /**
     * Would be nice to search for smth which is not a login.
     *
     * @param=login value to search for
     * @return      found login or NULL if there are no users w/ such login
     */
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
        if (Users.searchByLogin($login)) {
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
