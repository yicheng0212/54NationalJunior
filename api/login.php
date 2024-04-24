<?php

include 'db.php';   
$username = $_GET['username'] ?? '';
$password = $_GET['password'] ?? '';
$sql = "SELECT * FROM `users` WHERE `username` = '$username' AND `password` = '$password'";
$result = $pdo->query($sql)->fetch();
if (empty($result)) {
    echo 0;
    $_SESSION['username'] = $username;
} else {
    echo 1;
}