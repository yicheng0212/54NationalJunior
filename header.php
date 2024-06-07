<header class="shadow-sm p-3 d-flex w-100 bg-light">
    <img src="./img/logo.png" alt="LOGO" height=80px>
    <a class="text-reset text-decoration-none" href="index.php">
        <h3 class="text-secondary pt-2 mt-3">南港展覽館接駁專車系統</h1>
    </a>
    <div class="col-9 row align-items-center justify-content-end">
        <a href="admin.php" class="mx-2 text-reset text-decoration-none">系統管理</a>
        <?php if (isset($_SESSION['username'])) : ?><!--如果有設定session['username']-->
            <a href="./api/logout.php" class="mx-2 text-reset text-decoration-none">登出</a>
        <?php endif; ?>
    </div>
</header>