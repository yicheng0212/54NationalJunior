<div id="app" class="container mt-4">
        <h1 class="mb-4">接駁車管理</h1>

        <!-- 打開新增接駁車模態框的按鈕 -->
        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#busModal" @click="openModal">
            新增接駁車
        </button>

        <!-- 模態框 -->
        <div class="modal fade" id="busModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- 模態框頭部 -->
                    <div class="modal-header">
                        <h4 class="modal-title">{{ modalTitle }}</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- 模態框主體 -->
                    <div class="modal-body">
                        <form @submit.prevent="submitBus">
                            <div class="form-group">
                                <label for="busNumber">接駁車編號:</label>
                                <input type="text" class="form-control" id="busNumber" v-model="busNumber" :readonly="isEditing" required>
                            </div>
                            <div class="form-group">
                                <label for="drivenTime">行駛時間:</label>
                                <input type="number" class="form-control" id="drivenTime" v-model="drivenTime" min="0" required>
                            </div>
                            <button type="submit" class="btn btn-primary">提交</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- 接駁車列表表格 -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>接駁車編號</th>
                    <th>行駛時間</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="bus in buses" :key="bus.id">
                    <td>{{ bus.busNumber }}</td>
                    <td>{{ bus.drivenTime }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm m-1" @click="editBus(bus)">編輯</button>
                        <button class="btn btn-danger btn-sm m-1" @click="deleteBus(bus.id)">刪除</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <script>
        Vue.createApp({
            data() {
                return {
                    buses: [], // 接駁車列表
                    busNumber: '', // 接駁車編號
                    drivenTime: '', // 行駛時間
                    currentBus: null, // 當前編輯的接駁車
                    modalTitle: '新增接駁車' // 模態框標題
                };
            },
            mounted() {
                this.fetchBuses();//取得接駁車列表
            },
            methods: {
                fetchBuses() {//取得接駁車列表
                    fetch('./api/bus.php')
                        .then(response => response.json())//取得回應
                        .then(data => {
                            this.buses = data;//將取得的資料存入buses
                        });
                },
                submitBus() {//提交接駁車
                    if (this.currentBus) {
                        this.updateBus();//如果有當前編輯的接駁車，則更新接駁車
                    } else {
                        this.addBus();//否則新增接駁車
                    }
                },
                addBus() {//新增接駁車
                    fetch('./api/bus.php', {
                        method: 'POST',//使用POST方法
                        headers: {//設定標頭
                            'Content-Type': 'application/json'//告訴伺服器要使用json格式
                        },
                        body: JSON.stringify({//將資料轉為json格式
                            busNumber: this.busNumber,//接駁車編號
                            drivenTime: this.drivenTime
                        })
                    })
                    .then(response => response.json())//取得回應
                    .then(() => {//成功後
                        this.fetchBuses();//取得接駁車列表
                        this.resetForm();//重設表單
                        $('#busModal').modal('hide');//關閉模態框
                    });
                },
                editBus(bus) { // 編輯接駁車
                this.currentBus = bus; // 將bus存入currentBus
                this.busNumber = bus.busNumber;
                this.drivenTime = bus.drivenTime;
                this.modalTitle = '編輯接駁車'; // 設定模態框標題
                this.isEditing = true; // 設定編輯模式標誌
                $('#busModal').modal('show'); // 打開模態框
             },
                updateBus() {//更新接駁車
                    fetch('./api/bus.php', {
                        method: 'PUT',//使用PUT方法
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id: this.currentBus.id,
                            busNumber: this.busNumber,
                            drivenTime: this.drivenTime
                        })
                    })
                    .then(response => response.json())
                    .then(() => {
                        this.fetchBuses();
                        this.resetForm();
                        $('#busModal').modal('hide');
                    });
                },
                deleteBus(id) {//刪除接駁車
                    fetch('./api/bus.php', {//使用DELETE方法
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ id: id })//將id轉為json格式
                    })
                    .then(response => response.json())
                    .then(() => {
                        this.fetchBuses();
                    });
                },
                resetForm() {//重設表單
                    this.busNumber = '';
                    this.drivenTime = '';
                    this.currentBus = null;
                    this.modalTitle = '新增接駁車';
                },
                openModal() {//打開模態框
                    this.resetForm();
                    $('#busModal').modal('show');
                }
            }
        }).mount('#app');
    </script>