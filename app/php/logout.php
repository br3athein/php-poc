<?php
include 'secure.php';

session_destroy();
header('Location: index.htm');
die();
