<?php
// session_start();
// if (!isset($_SESSION['username'])){
//     header('Location:login.php');
//     exit();
// }
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
    <div class="container mt-5 p-3">
        <?php $pos = $_GET['pos'] ?? 'bus'; ?><!--這裡的 $_GET['pos'] 是用來判斷是要顯示接駁車管理還是站點管理-->
        <div class="border p-3">
            <a class="btn <?php echo $pos == 'bus' ? 'btn-primary' : 'btn-light'; ?>" href="?pos=bus">接駁車管理</a><!--這裡的 ?pos=bus 是用來切換顯示接駁車管理的畫面-->
            <a class="btn <?php echo $pos == 'station' ? 'btn-primary' : 'btn-light'; ?>" href="?pos=station">站點管理</a>
        </div>
        <?php include "admin$pos.php"; ?><!--這裡的 admin$pos.php 是用來顯示接駁車管理或站點管理的畫面-->
    </div>
</body>

</html>
</body>
</html>