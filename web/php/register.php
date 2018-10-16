<?php
$conn = new mysqli('db', getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'), getenv('MYSQL_DATABASE'));

$requested_login = $_POST['login'];
$raw_pwd = $_POST['pwd'];
$hashed_pwd = password_hash($raw_pwd, PASSWORD_DEFAULT);

if (empty($requested_login) || empty($raw_pwd)) {
    die('You must provide both login and password in order to create an account.');
}

$query = 'SELECT id FROM users WHERE login=? LIMIT 1';
if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param('s', $requested_login);
    $stmt->execute();
    if ($stmt->fetch()) {
        die('User w/ login ' . $requested_login . ' exists already.');
    } else {
        // registration_date? is it handled automatically? no clue m8
        $query = 'INSERT INTO users (login, password_hash) VALUES (?, ?)';
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param(
                'ss',
                $requested_login,
                $hashed_pwd
            );
            if ($stmt->execute() === TRUE) {
                echo 'user ' . $requested_login . ' was successfully created.';
                header('Location: index.htm');
                die();
            } else {
                echo 'user ' . $requested_login . ' wasn\'t successfully created.';
            }
        }
    }
}
