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
                        <img :src="captchaSrc" id="captchaImage" alt="驗證碼" />
                        <button class="btn btn-sm btn-secondary" type="button" @click="refreshCaptcha">重新產生</button>
                        <input type="text" class="form-control" id="captcha" v-model="captcha" required>
                    </div>
                    <button type="submit" class="btn btn-success btn-lg btn-block">送出</button>
                </form>
    </div>

    <script>
        Vue.creatApp({
            data() {
                return {
                    username: '',
                    password: '',
                    captcha: '',
                    captchaSrc: './api/captcha.php'
                }
            },
            methods: {
                refreshCaptcha() {
                    this.captchaSrc = './api/captcha.php?' + Math.random();
                },
                submitForm() {
                    const data = {
                        username: this.username,
                        password: this.password,
                        captcha: this.captcha
                    };
                    fetch('./api/login.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            alert('登入成功');
                            location.href = 'admin.php';
                        } else {
                            alert('登入失敗');
                            this.refreshCaptcha();
                        }
                    });
                }
            }
        }).mount('#app');
    </script>
</body>
</html>