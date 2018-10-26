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

$registeredUser = (Users::search(login, $requestedLogin));
if ($registeredUser) {
    die('User w/ login ' . $requestedLogin . ' exists already.');
}
$new_user = Users::create(
    array(
        login => $requestedLogin,
        pwd_hash => $hashed_pwd,
    )
);
