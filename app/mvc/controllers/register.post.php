<?php
/**
 * Actually create user in a database.
 */

$requestedLogin = $_POST['login'];
$rawPwd = $_POST['pwd'];

if (empty($requestedLogin) || empty($rawPwd)) {
    die('You must provide both login and password in order to create an account.');
}

$hashed_pwd = password_hash($rawPwd, PASSWORD_DEFAULT);
require '/app/mvc/models/users.php';

$alreadyExists = (Users.searchByLogin($requestedLogin));
if ($alreadyExists) {
    die('User w/ login ' . $requestedLogin . ' exists already.');
}
