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

        <div v-if="busInfo" class="mt-3">
            <h4>您的接駁車班次資訊</h4>
            <p>接駁車編號: {{ busInfo.bus_number }}</p>
            <p>乘客名單: {{ busInfo.passengers.join(', ') }}</p>
        </div>
    </div>
    <script>
        const app = Vue.createApp({
            data() {
                return {
                    email: '',
                    message: '',
                    busInfo: null
                };
            },
            methods: {
                searchBus() {
                    fetch(`./api/result.php?email=${this.email}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.message) {
                                this.message = data.message;
                                this.busInfo = null;
                            } else {
                                this.busInfo = data;
                                this.message = '';
                            }
                        });
                }
            }
        });

        app.mount('#app');
    </script>
</body>
</html>
