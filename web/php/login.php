<?php

$search_login = $_POST['login'];
$search_pwd = $_POST['pwd'];

$conn = new mysqli('db', getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'), getenv('MYSQL_DATABASE'));

if ($conn->connect_error) {
    die('Failed to connect: (' . $conn->connect_errno . ') ' . $conn->connect_error);
}

$query = 'SELECT login, password_hash FROM users WHERE login = ?';
if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param('s', $search_login);
    $stmt->execute();
    $stmt->bind_result($user_login, $user_pwd_hash);
    $stmt->fetch();

    // echo var_dump($hashed_search_pwd) . '<br>';
    // echo var_dump($user_pwd_hash) . '<br>';
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

// isn't it done implicitly?..
$conn->close();
