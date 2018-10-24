<?php

require '../dbconn.php';
$conn = establish_dbconn();

$search_login = $_POST['login'];
$search_pwd = $_POST['pwd'];

$query = 'SELECT login, password_hash FROM users WHERE login = ?';
if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param('s', $search_login);
    $stmt->execute();
    $stmt->bind_result($user_login, $user_pwd_hash);
    $stmt->fetch();

    if ($user_login && password_verify($search_pwd, $user_pwd_hash)) {
        session_start();
        $_SESSION['login'] = $search_login;
        $_SESSION['pwd_hash'] = $user_pwd_hash;
        header('Location: insight.php');
        die();
    } else {
        die('Login failed for ' . $search_login . ': wrong combination of credentials.');
    }
}
