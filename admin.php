<?php
session_start();
if (!isset($_SESSION['username'])){
    header('Location:login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>網站管理</title>
    <?php include 'link.php'; ?>
</head>
<body class="bg-light">
    <?php include 'header.php'; ?>
        <div id="app" class="container">

        </div>
</body>
</html>