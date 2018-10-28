<?php

require '/app/mvc/models/users.php';

$givenLogin = $_POST['login'];
$givenPassword = $_POST['pwd'];

$Users = new Users();

try {
    $requestedUser = $Users::search('login', $givenLogin)[0];
    $requestedUser->authenticate($givenPassword);
} catch (orm\AuthException $e) {
    echo 'login failed for ' . $givenLogin . 'Try another pwd, lol.';
}
