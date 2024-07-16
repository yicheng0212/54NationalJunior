<div id="app" class="container mt-4">
    <h1 class="mb-4">站點管理</h1>

    <!-- 打開新增站點模態框的按鈕 -->
    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#stationModal" @click="openModal">
        新增站點
    </button>

    <!-- 模態框 -->
    <div class="modal fade" id="stationModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- 模態框頭部 -->
                <div class="modal-header">
                    <h4 class="modal-title">{{ modalTitle }}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- 模態框主體 -->
                <div class="modal-body">
                    <form @submit.prevent="submitStation">
                        <div class="form-group">
                            <label for="stationName">站點名稱:</label>
                            <input type="text" class="form-control" id="stationName" v-model="stationName" :readonly="isEditing" required>
                        </div>
                        <div class="form-group">
                            <label for="drivenTime">行駛時間 (分鐘):</label>
                            <input type="number" class="form-control" id="drivenTime" v-model="drivenTime" min="0" required>
                        </div>
                        <div class="form-group">
                            <label for="stopTime">停留時間 (分鐘):</label>
                            <input type="number" class="form-control" id="stopTime" v-model="stopTime" min="0" required>
                        </div>
                        <button type="submit" class="btn btn-primary">提交</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- 站點列表表格 -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>站點名稱</th>
                <th>行駛時間 (分鐘)</th>
                <th>停留時間 (分鐘)</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="station in stations" :key="station.id">
                <td>{{ station.stationName }}</td>
                <td>{{ station.drivenTime }}</td>
                <td>{{ station.stopTime }}</td>
                <td>
                    <button class="btn btn-warning btn-sm m-1" @click="editStation(station)">編輯</button>
                    <button class="btn btn-danger btn-sm m-1" @click="deleteStation(station.id)">刪除</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script>
    Vue.createApp({
        data() {
            return {
                stations: [], // 站點列表
                stationName: '', // 站點名稱
                drivenTime: '', // 行駛時間
                stopTime: '', // 停留時間
                currentStation: null, // 當前編輯的站點
                modalTitle: '新增站點' // 模態框標題
            };
        },
        mounted() {
            this.fetchStations(); // 取得站點列表
        },
        methods: {
            fetchStations() { // 取得站點列表
                fetch('./api/station.php')
                    .then(response => response.json()) // 取得回應
                    .then(data => {
                        this.stations = data; // 將取得的資料存入stations
                    });
            },
            submitStation() { // 提交站點
                if (this.currentStation) {
                    this.updateStation(); // 如果有當前編輯的站點，則更新站點
                } else {
                    this.addStation(); // 否則新增站點
                }
            },
            addStation() { // 新增站點
                fetch('./api/station.php', {
                    method: 'POST', // 使用POST方法
                    headers: { // 設定標頭
                        'Content-Type': 'application/json' // 告訴伺服器要使用json格式
                    },
                    body: JSON.stringify({ // 將資料轉為json格式
                        stationName: this.stationName, // 站點名稱
                        drivenTime: this.drivenTime, // 行駛時間
                        stopTime: this.stopTime // 停留時間
                    })
                })
                .then(response => response.json()) // 取得回應
                .then(() => { // 成功後
                    this.fetchStations(); // 取得站點列表
                    this.resetForm(); // 重設表單
                    $('#stationModal').modal('hide'); // 關閉模態框
                });
            },
            editStation(station) { // 編輯站點
                this.currentStation = station; // 將station存入currentStation
                this.stationName = station.stationName;
                this.drivenTime = station.drivenTime;
                this.stopTime = station.stopTime;
                this.modalTitle = '編輯站點'; // 設定模態框標題
                this.isEditing = true; // 設定編輯模式標誌
                $('#stationModal').modal('show'); // 打開模態框
            },
            updateStation() { // 更新站點
                fetch('./api/station.php', {
                    method: 'PUT', // 使用PUT方法
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id: this.currentStation.id,
                        stationName: this.stationName,
                        drivenTime: this.drivenTime,
                        stopTime: this.stopTime
                    })
                })
                .then(response => response.json())
                .then(() => {
                    this.fetchStations();
                    this.resetForm();
                    $('#stationModal').modal('hide');
                });
            },
            deleteStation(id) { // 刪除站點
                fetch('./api/station.php', { // 使用DELETE方法
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id }) // 將id轉為json格式
                })
                .then(response => response.json())
                .then(() => {
                    this.fetchStations();
                });
            },
            resetForm() { // 重設表單
                this.stationName = '';
                this.drivenTime = '';
                this.stopTime = '';
                this.currentStation = null;
                this.modalTitle = '新增站點';
            },
            openModal() { // 打開模態框
                this.resetForm();
                $('#stationModal').modal('show');
            }
        }
    }).mount('#app');
</script>