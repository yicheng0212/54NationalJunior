<div id="app" class="container mt-4">
        <h1 class="mb-4">表單管理</h1>

        <div class="form-group">
            <label for="formStatus">是否啟用表單:</label>
            <select id="formStatus" class="form-control" v-model="formStatus">
                <option value="1">啟用</option>
                <option value="0">停用</option>
            </select>
        </div>
        <button @click="updateSettings" class="btn btn-primary">更新設定</button>
        <button @click="generateBuses" class="btn btn-success">生成接駁車</button>

        <h2 class="mt-4">當前需派遣接駁車輛數</h2>
        <p>{{ busCount }}</p>
    </div>

    <script>
        const app = Vue.createApp({
            data() {
                return {
                    formStatus: '1',
                    busCount: 0
                };
            },
            mounted() {
                this.fetchSettings();
                this.fetchBusCount();
            },
            methods: {
                fetchSettings() {
                    fetch('./api/form.php?type=settings')
                        .then(response => response.json())
                        .then(data => {
                            this.formStatus = data.form_enabled ? '1' : '0';
                        });
                },
                updateSettings() {
                    fetch('./api/form.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            form_enabled: this.formStatus === '1'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                    });
                },
                generateBuses() {
                    fetch('./api/generateBuses.php', {
                        method: 'PUT'
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        this.fetchBusCount();
                    });
                },
                fetchBusCount() {
                    fetch('./api/participants.php?type=count')
                        .then(response => response.json())
                        .then(data => {
                            this.busCount = Math.ceil(data.count / 50);
                        });
                }
            }
        });

        app.mount('#app');
    </script>