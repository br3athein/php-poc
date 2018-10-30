<?php

$query = 'SELECT id FROM users WHERE login=? LIMIT 1';
if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param('s', $requested_login);
    $stmt->execute();
    if ($stmt->fetch()) {
        die('User w/ login ' . $requested_login . ' exists already.');
    } else {
    }
}
