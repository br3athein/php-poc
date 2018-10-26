<?php

require '../models/users.php';

$givenLogin = $_POST['login'];
$givenPassword = $_POST['pwd'];

if (!$requestedUser = Users.search(login, $givenLogin)) {
    throw AuthException('Username not found, noone to authenticate.');
}

$requestedUser->authenticate($givenPassword);
