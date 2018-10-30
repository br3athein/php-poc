<?php
session_start();

$login = $_SESSION['login'];
$pwd_hash = $_SESSION['pwd_hash'];

if (!isset($login) || !isset($pwd_hash)) {
    echo "Session vars are absent. <a href='index.htm'>GTFO.</a>";
    die();
}
$conn = new mysqli(
    'db', getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'),
    getenv('MYSQL_DATABASE'));
if ($stmt = $conn->prepare(
        'SELECT password_hash FROM users WHERE login=? LIMIT 1')) {
    $stmt->bind_param('s', $login);
    $stmt->execute();
    $stmt->bind_result($actual_pwd_hash);
    $stmt->fetch();
    if ($pwd_hash !== $actual_pwd_hash) {
        die('This is the handmade kind of 403. GTFO.');
    }
}
