<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>網站管理--登入</title>
    <?php include 'link.php'; ?>
</head>
<body class="bg-light">
<?php include 'header.php'; ?>
    <div id="app" class="container">
    <form @submit.prevent="submitForm" class="mt-5">
                    <h2 class="text-center">網站管理--登入</h2>
                    <div class="form-group">
                        <label for="username">帳號:</label>
                        <input type="text" class="form-control" id="username" v-model="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">密碼:</label>
                        <input type="password" class="form-control" id="password" v-model="password" required>
                    </div>
                    <div class="form-group">
                        <label for="captcha">圖形驗證碼:</label>
                        <div class="btn btn-primary m-2" id="captcha">{{ captchaSrc }}</div>
                        <button class="btn btn-sm btn-secondary" type="button" @click="refreshCaptcha">重新生成</button>
                        <input type="text" class="form-control" id="captcha" v-model="captcha" required>
                    </div>
                    <button type="submit" class="btn btn-success btn-lg btn-block">送出</button>
                </form>
    </div>

    <script>
  Vue.createApp({
        data(){
            return {
                username : '',
                password : '',
                captcha : '',
                captchaSrc : '<?= $_SESSION['code'] = rand(1000, 9999); ?>',
            };
        },
        methods:{
            submitForm(){
                fetch('./api/login.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        username: this.username,
                        password: this.password,
                        captcha: this.captchaSrc,
                    }),
                })
                .then(response => response.json())
                .then(data => {
                if(data.status === 'success'){  
                    alert('登入成功');
                    console.log(data);
                    location.href = './';
                }else{
                    alert('登入失败');
                    console.log(data);
                    this.refreshCaptcha();
                }
                })
            },
            refreshCaptcha(){
                fetch('./api/captcha.php')
                    .then(response => response.text())
                    .then(data => {
                        this.captchaSrc = data;
                    })
            },
        },
    }).mount('#app');
    </script>
</body>
</html>