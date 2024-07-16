<?php session_start(); ?>
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

        <div class="map shadow rounded mt-5" style="width: 65vw; height: 50vh; overflow: auto;" :style="{ '--w': ` calc((65vw - 20em) / ${data.row})` }">
            <div class="row" v-for="(row, i) in data.data" :class="i % 2 ? 'left-row' : 'right-row'" style="width: 65wv - 20em; display: flex;">
                <div class="border-left"></div>
                <div class="station" v-for="(item, idx) in row">
                    <div class="mapIcon"></div>
                    <div class="data text-center" v-if="item.bus.length > 0">
                        <p style="transform: translateY(-4em);" :class="item.bus[0].textColor" v-html="item.bus[0].htmlContent"></p>
                        <button class="btn btn-sm btn-outline-dark" style="transform: translateY(-2em);">{{
                            item.stationName }}</button>
                        <div class="card shadow busData p-1">
                            <p v-for="bus in item.bus" class="m-0" :class="bus.textColor" v-html="bus.htmlContentForCard"></p>
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

                const data = reactive({
                    row: 3,
                    data: []
                })

                let station, bus = undefined

                const procData = () => {
                    if (!station || typeof bus === "undefined") return;

                    let allTime = 0;
                    console.log("Starting procData");

                    station.forEach((stationInfo, idx) => {
                        allTime += stationInfo.drivenTime + stationInfo.stopTime;
                        stationInfo.bus = [];

                        let passedBuses = [];

                        bus.forEach((busInfo) => {
                            let busCopy = {
                                ...busInfo
                            };
                            if (busCopy.drivenTime <= allTime) {
                                if (busCopy.drivenTime >= allTime - stationInfo.stopTime) {
                                    busCopy.textColor = "text-danger";
                                    busCopy.htmlContent = busCopy.busNumber + "<br>已到站";
                                    busCopy.htmlContentForCard = busCopy.busNumber + "已到站";
                                    stationInfo.bus.push(busCopy);
                                } else {
                                    busCopy.textColor = "text-dark";
                                    busCopy.relArri = allTime - stationInfo.stopTime - busCopy.drivenTime;
                                    busCopy.htmlContent = busCopy.busNumber + "<br>約" + busCopy.relArri + "分鐘";
                                    busCopy.htmlContentForCard = busCopy.busNumber + "約" + busCopy.relArri + "分鐘";
                                    stationInfo.bus.push(busCopy);
                                }
                            } else {
                                busCopy.textColor = "text-secondary";
                                busCopy.htmlContent = busCopy.busNumber + "<br>已過站";
                                busCopy.htmlContentForCard = busCopy.busNumber + "已過站";
                                passedBuses.push(busCopy);
                            }
                        });
                        stationInfo.bus = stationInfo.bus.concat(passedBuses);
                        stationInfo.bus.splice(3);
                    });

                    data.data = [];
                    let rowCount = Math.ceil(station.length / data.row);
                    for (let i = 0; i < rowCount; i++) {
                        data.data.push(station.slice(i * data.row, (i + 1) * data.row));
                    }

                    console.log("Processed Data:", data.data);
                };


                const getData = async () => {
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


                onMounted(() => {
                    getData()
                    setInterval(() => {
                        procData()
                    }, 1000)
                })

                return {
                    data,
                    getData,
                    procData
                }

            }
        }).mount("#app")
    </script>
</body>

</html>
