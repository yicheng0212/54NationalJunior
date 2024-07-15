<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>台北101接駁專車班次查詢</title>
    <?php include 'link.php'; ?>
</head>
<body>
    <?php include 'header.php'; ?>
    <div id="app" class="container mt-4">
        <h1 class="mb-4">班次查詢</h1>

        <form @submit.prevent="searchBus">
            <div class="form-group">
                <label for="email">信箱:</label>
                <input type="email" class="form-control" id="email" v-model="email" required>
            </div>
            <button type="submit" class="btn btn-primary">查詢</button>
        </form>

        <div v-if="message" class="mt-3">
            <p>{{ message }}</p>
        </div>

        <div v-if="bus_number" class="mt-3">
            <h4>您的接駁車班次資訊</h4>
            <p>接駁車編號: {{ bus_number }}</p>
        </div>
    </div>
    <script>
        const app = Vue.createApp({
            data() {
                return {
                    email: '',
                    message: '',
                    bus_number: ''
                };
            },
            methods: {
                searchBus() {
                    fetch(`./api/result.php?email=${this.email}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.bus_number) {
                                this.bus_number = data.bus_number;
                                this.message = data.message;
                            } else {
                                this.message = data.message;
                                this.bus_number = '';
                            }
                        })
                        .catch(error => {
                            this.message = '查詢過程中發生錯誤，請稍後再試。';
                            this.bus_number = '';
                        });
                }
            }
        });

        app.mount('#app');
    </script>
</body>
</html>
