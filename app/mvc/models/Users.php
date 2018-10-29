<?php
/**
 * Users model.
 *
 * Represents `users` table in a database.
 */

namespace app\models;

\Model::useConnection(\establishDatabaseConn(), 'mydb');

class Users extends \app\lib\Model {
    /**
     * Try to authenticate using given credentials.
     */
    public function authenticate($pwd) {
        if (password_verify($pwd, $this->password_hash)) {
            session_start();
            $_SESSION['login'] = $this->login;
            $_SESSION['pwd_hash'] = $this->password_hash;
            return true;
        }
        throw new \orm\AuthException();
    }
    public function verifyPwdHash($pwdHash) {
        if ($pwdHash !== $this->password_hash) {
            throw new \orm\AuthException('Such hash mismatch. Wow.');
        }
        return;
    }

    public static function getByLogin($login) {
        return self::retrieveByField('login', $login, \orm\Model::FETCH_ONE);
    }

    public function register($login, $pwd) {
        if ($this->getByLogin($login)) {
            throw new \orm\OrmException(
                'FATAL: user "' . $login . '" exists already.'
            );
        }

        $user = new Users;
        $user->login = $login;
        $user->password_hash = password_hash($pwd, PASSWORD_DEFAULT);
        $user->save();
    }
}
