<?php

namespace secure;

require_once '/app/mvc/models/users.php';

session_start();



$conn = establishDatabaseConn();
\Users::useConnection($conn, 'mydb');

function getCurrentUser() {
    return \Users::getByLogin($_SESSION['login']);
}

/**
 * Check whether the guy is logged in.
 */
function authorized() {
    return getCurrentUser() !== null;
}

/**
 * Kick off the guy if he's not authorized.
 */
function requireAuth() {
    if (authorized()) {
        throw \orm\AuthException('GTFO');
    }
}

$pwdHash = $_SESSION['pwd_hash'];
if (isset($login) && isset($pwdHash)) {
    getCurrentUser()->verifyPwdHash($pwdHash);
}
