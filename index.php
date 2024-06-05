<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>首頁</title>
    <?php include 'link.php'; ?>

    <style>
        .map {
            padding-top: 10em;
            background-color: #fff;
            transition: .3s;
            display: grid;
            justify-content: center;
        }

        .mapIcon {
            position: absolute;
            width: 1em;
            height: 1em;
            background-color: var(--blue);
            border-radius: 50%;
            box-shadow: 0 0 0 .2em #fff, 0 0 0 .4em var(--blue);
            transition: .3s;
            z-index: 100;
        }

        .mapIcon:hover {
            transform: scale(1.2);
        }

        .station {
            position: relative;
            width: calc(var(--w));
            display: flex;
            justify-content: center;
            align-items: flex-start;
            height: 200px;
        }

        .station::after,
        .station::before {
            content: "";
            position: absolute;
            height: 1em;
            width: calc(var(--w) / 2);
            background-color: var(--info);
        }

        .station::after {
            left: 0;
        }

        .station::before {
            right: 0;
        }

        .row {
            position: relative;
            width: calc(65vw - 20em);
            margin-top: -1em;
            display: flex;
        }

        .right-row {
            flex-direction: row;
        }

        .left-row {
            flex-direction: row-reverse;
        }

        .row:first-of-type>:nth-child(2)::after,
        .row:last-of-type.left-row>:nth-last-child(2)::after,
        .row:last-of-type.right-row>:nth-last-child(2)::before {
            content: none;
        }

        .border-left,
        .border-right {
            width: 1em;
            height: 200px;
            background-color: var(--info);
            display: none;
            position: absolute;
        }

        .border-left {
            left: -10px;
            border-radius: 10px 0 0 10px;
        }

        .border-right {
            right: -10px;
            border-radius: 0 10px 10px 0;
        }

        .left-row>.border-left {
            display: inline;
        }

        .right-row>.border-right {
            display: inline;
        }

        .right-row:last-of-type>div:last-child,
        .left-row:last-of-type>div:first-child {
            display: none;
        }

        .mapIcon~.data .busData {
            max-width: var(--w);
            transform: translateY(-12em);
            transition: .3s;
            opacity: 0;
            z-index: 0;
        }

        .mapIcon:hover~.data .busData {
            opacity: 1;
            z-index: 200;
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="container my-5" id="app">
        <div class="card w-25 p-1 shadow">
            <label for="busRange" class="text-center">地圖每列顯示
                <select v-model="data.row" class="custom-select" style="width: auto;">
                    <option :value="idx" v-for="idx in 5">{{ idx }}站</option>
                </select>
            </label>
            <input type="range" min="1" max="5" id="busRange" v-model="data.row" class="custom-range" @click="getData">
        </div>

        <div class="map shadow rounded mt-5" style="width: 65vw; height: 50vh; overflow: auto;" :style="{ '--w': ` calc((65vw - 20em) / ${data.row})` }"><!-- 這裡的 65vw 是地圖的寬度，50vh 是地圖的高度 -->
            <div class="row" v-for="(row, i) in data.data" :class="i % 2 ? 'left-row' : 'right-row'" style="width: 65wv - 20em; display: flex;">
                <div class="border-left"></div>
                <div class="station" v-for="(item, idx) in row">

                    <div class="mapIcon"></div>
                    <div class="data text-center">
                        <p style="transform: translateY(-4em);" :class="item.bus[0].textColor" v-html="item.bus[0].htmlContent"></p>
                        <button class="btn btn-sm btn-outline-dark" style="transform: translateY(-2em);">{{
                                    item.stationName }}</button>
                        <div class="card shadow busData p-1">
                            <p v-for="bus in item.bus" class="m-0" :class="bus.textColor" v-html="bus.htmlContentForCard">
                            </p>
                        </div>
                    </div>

                </div>
                <div class="border-right"></div>
            </div>
        </div>

    </div>
    <script>
        const {
            createApp,
            reactive,
            onMounted
        } = Vue
        createApp({
            setup() {

                const data = reactive({ // 這裡的 data 是用來存放地圖上的站點資訊
                    row: 3,
                    data: []
                })

                let station, busData = undefined // 這裡的 station 是用來存放站點資訊，busData 是用來存放接駁車資訊

                const procData = () => { // 這裡的 procData 是用來處理資料的函式
                    if (!station || typeof bus === "undefined") return; // 如果 station 或 busData 還沒有資料，就不處理資料

                    let allTime = 0; // 這裡的 allTime 是用來計算總時間的變數
                    console.log("Starting procData"); // 這裡的 console.log 用來顯示訊息

                    station.forEach((stationInfo, idx) => { // 這裡的 forEach 用來遍歷 station 陣列
                        allTime += stationInfo.drivenTime + stationInfo.stopTime; // 使用 drivenTime 和 stopTime
                        stationInfo.bus = []; // 這裡的 bus 是用來存放接駁車資訊

                        bus.forEach((busInfo) => { // 這裡的 forEach 用來遍歷 bus 陣列
                            let busCopy = { // 這裡的 busCopy 是用來複製 busInfo 物件
                                ...busInfo
                            };
                            if (busCopy.drivenTime <= allTime) { // 使用 drivenTime
                                if (busCopy.drivenTime >= allTime - stationInfo.stopTime) { // 使用 stopTime
                                    busCopy.textColor = "text-danger";
                                    busCopy.htmlContent = busCopy.busNumber + "<br>已到站";
                                    busCopy.htmlContentForCard = busCopy.busNumber + "已到站";
                                } else {
                                    busCopy.textColor = "text-dark";
                                    busCopy.relArri = allTime - stationInfo.stopTime - busCopy.drivenTime; // 使用 drivenTime 和 stopTime
                                    busCopy.htmlContent = busCopy.busNumber + "<br>約" + busCopy.relArri + "分鐘";
                                    busCopy.htmlContentForCard = busCopy.busNumber + "約" + busCopy.relArri + "分鐘";
                                }
                            } else {
                                busCopy.textColor = "text-secondary";
                                busCopy.relArri = allTime - stationInfo.stopTime - busCopy.drivenTime; // 使用 drivenTime 和 stopTime
                                busCopy.htmlContent = idx == 0 ? "未發車" : busCopy.busNumber + "已過站";
                                busCopy.htmlContentForCard = idx == 0 ? "未發車" : busCopy.busNumber + "已過站";
                            }
                            stationInfo.bus.push(busCopy); // 這裡的 push 用來將 busCopy 加入 stationInfo.bus
                        });

                        stationInfo.bus.sort((a, b) => a.relArri - b.relArri); // 這裡的 sort 用來排序 stationInfo.bus
                        stationInfo.bus.splice(3); // 這裡的 splice 用來刪除 stationInfo.bus 的元素
                    });

                    data.data = []; // 這裡的 data.data 是用來存放地圖上的站點資訊
                    let rowCount = Math.ceil(station.length / data.row); // 這裡的 rowCount 是用來計算列數
                    for (let i = 0; i < rowCount; i++) { // 這裡的 for 迴圈用來遍歷 rowCount
                        data.data.push(station.slice(i * data.row, (i + 1) * data.row));
                    }

                    console.log("Processed Data:", data.data); // 這裡的 console.log 用來顯示訊息
                };


                const getData = async () => { // 這裡的 getData 是用來取得資料的函式
                    await $.getJSON("./api/station.php", {}, (r) => {
                        console.log("Station Data:", r);
                        station = structuredClone(r);
                        procData();
                    });
                    await $.getJSON("./api/bus.php", {}, (r) => {
                        console.log("Bus Data:", r);
                        bus = structuredClone(r);
                        procData();
                    });
                };


                onMounted(() => { // 這裡的 onMounted 用來執行函式
                    getData()
                    setInterval(() => {
                        procData()
                    }, 1)
                })

                return { // 這裡的 return 用來回傳資料
                    data,
                    getData,
                    procData
                }

            }
        }).mount("#app")
    </script>
</body>

</html>