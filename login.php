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
                        <label for="captcha">驗證碼:</label>
                        <div class="btn btn-primary m-2" id="captcha">{{ captcha }}</div>
                        <button class="btn btn-sm btn-secondary" type="button" @click="reGenerate">重新生成</button>
                        <input type="text" class="form-control" id="verification" v-model="verification" required>
                    </div>
                    <button type="submit" class="btn btn-success btn-lg btn-block">送出</button>
                </form>
    </div>

    <script>
  Vue.createApp({
        data(){
            return {
                username: "",
                password: "",
                captcha: "",
                verification: ""
            };
        },
        methods:{
            submitForm(){
                if (this.verification == this.captcha) {
                    $.get("./api/login.php", { username: this.username, password: this.password }, (r) => {
                        if (r == 0) {
                            alert("登入成功")
                            location.href = "admin.php"
                        } else {
                            alert("登入失敗")
                        }
                    })
                } else {
                    alert("驗證碼錯誤，哈哈笑死")
                }
            },
            reGenerate() {
                this.captcha = Math.floor(Math.random() * 8999 + 1000)
            }
        },
        mounted() {
            this.captcha = Math.floor(Math.random() * 8999 + 1000)
        }
    }).mount('#app');
    </script>
</body>
</html>