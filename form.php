<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>搭乘意願調查子系統</title>
    <?php include 'link.php'; ?>
</head>
<body>
<?php include 'header.php'; ?>
    <div id="app">
        <div class="container">
        <form @submit.prevent="submitForm" class="bg-light border rounded shadow p-5 mt-5">
            <h2 class="text-center">搭乘意願調查子系統</h2>
            <div class="form-group">
                <label for="name" class="bg-white px-3 border rounded">姓名:</label>
                <input type="text" class="form-control" id="name" v-model="this.name" required placeholder="Provide your name">
            </div>
            <div class="form-group">
                <label for="phone" class="bg-white px-3 border rounded">電話:</label>
                <input type="text" class="form-control" id="phone" v-model="this.phone" required placeholder="Provide your phone number">
            </div>
        </form>
    </div>
</div>
</body>
</html>