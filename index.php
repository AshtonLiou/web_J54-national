<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>台北 101 接駁專車</title>
    <link rel="stylesheet" href="./jquery/jquery-ui.css">
    <link rel="stylesheet" href="./bootstrap/bootstrap.css">
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="./img/taipei101.png" type="image/x-icon">
</head>

<body>

    <div id="app" v-cloak>

        <div class="bgImg"></div>

        <div class="card p-3 shadow border border-primary font-weight-bold"
            style="position: fixed; bottom: 1em; right: 1em; z-index: 999;">&#9673; 路網圖{{ data.renewTime }}秒前更新</div>

        <!-- header -->

        <header>
            <nav class="navbar navbar-expand bg-light p-3 shadow" style="height: 15vh;">
                <a href="./index.php">
                    <img src="./img/taipei101.png" alt="taipei101-logo"
                        style="height: 8vh; margin: 2vh 1vw; -webkit-user-drag: none;">
                </a>
                <h2 class="m-3 textHover" style="font-weight: 600;" onclick="location.href = './index.php'">台北 <span
                        style="color: var(--info);">101</span> 接駁專車系統</h2>
                <a href="./index.php" class="btn infoBtn m-3 btn-info disabled">&#9673; 路網圖</a>
                <a href="./search.php" class="btn infoBtn m-3">&#8981; 班次查詢</a>
                <a href="./manage.php" class="btn infoBtn m-3">&#9881; 系統管理</a>
            </nav>
        </header>

        <!-- main -->

        <main>

            <div class="my-4" style="display: flex; align-items: center; flex-direction: column;">

                <!-- map -->

                <div class="map mt-5 shadow rounded" :style="{ '--w': ` calc((65vw - 20em) / ${data.row})` }">

                    <div class="row" v-for="(row, i) in data.data" :class="i % 2 ? 'left-row' : 'right-row'">

                        <div class="border-left"></div>
                        <div class="station" v-for="item in row">

                            <div class="mapIcon"></div>
                            <div class="data font-weight-bold">

                                <!-- current station status -->

                                <div class="text-center"
                                    style="width: var(--w); position: absolute; top: -4em; left: 0;">
                                    <p :class="item.bus[0].textColor" v-html="item.bus[0].htmlContent"></p>
                                </div>

                                <!-- station name -->

                                <div class="text-center"
                                    style="width: var(--w); position: absolute; top: 2em; left: 0;">
                                    <p v-html="item.name"></p>
                                </div>

                                <!-- station details -->

                                <div style="width: var(--w); height: 100px; position: absolute; left: 0; display: flex; justify-content: center; align-items: center;"
                                    :style="{ 'top': item.bus.length == 1 ? '-45%' : item.bus.length == 2 ? '-50%' : '-55%' }">
                                    <div class="busData card p-2 shadow text-left border border-secondary"
                                        style="flex-direction: column-reverse;">
                                        <p v-for="bus in item.bus" class="m-0" :class="bus.textColor"
                                            v-html="bus.htmlContentDetails"></p>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <div class="border-right"></div>

                    </div>

                </div>

            </div>

        </main>

    </div>

    <!-- script -->

    <script src="./jquery/jquery.js"></script>
    <script src="./jquery/jquery-ui.min.js"></script>
    <script src="./jquery/vue3.global.js"></script>
    <script src="./bootstrap/bootstrap.js"></script>
    <script>

        const { createApp, reactive, onMounted } = Vue
        createApp({
            setup() {

                const data = reactive({
                    renewTime: 0,
                    data: []
                })

                let stationData = undefined

                const getData = async () => {
                    await $.get("./api/get.php", { mode: "getRowNum" }, (r) => {
                        data.row = r
                    })
                    await $.getJSON("./api/get.php", { mode: "getBusData" }, (r) => {
                        busData = structuredClone(r)
                    })
                    await $.getJSON("./api/get.php", { mode: "getStationData" }, (r) => {
                        stationData = structuredClone(r)
                        processData()
                    })
                }

                const processData = () => {
                    const bestowBusData = (bus, station, relArri, textColor, htmlContent, htmlContentDetails) => {
                        bus.relArri = relArri
                        bus.textColor = textColor
                        bus.htmlContent = htmlContent
                        bus.htmlContentDetails = htmlContentDetails
                        station.bus.push(bus)
                    }
                    if (!stationData) return
                    let allTime = 0
                    $.each(stationData, (idx, station) => {
                        allTime += station.time + station.wait
                        station.bus = []
                        let noDeparting = true
                        $.each(busData, (index, busInfo) => {
                            let bus = structuredClone(busInfo)
                            let relArri = allTime - station.wait - bus.time
                            if (bus.time <= allTime) {
                                if (bus.time >= allTime - station.wait) {
                                    bestowBusData(bus, station, 0, "text-danger", `${bus.name}<br>已到站`, `&#9673;&nbsp;${bus.name}已到站`)
                                } else {
                                    bestowBusData(bus, station, relArri, "text-dark", `${bus.name}<br>約${relArri}分鐘`, `&#9951;&nbsp;${bus.name}約${relArri}分鐘`)
                                }
                            } else if (noDeparting) {
                                bestowBusData(bus, station, relArri, "text-secondary", `<br>未發車`, `&#10008;&nbsp;未發車`)
                                noDeparting = false
                            }
                        })
                        station.bus.sort((a, b) => {
                            if (a.relArri < 0 || b.relArri < 0) return b.relArri - a.relArri
                            return Math.abs(a.relArri) - Math.abs(b.relArri)
                        }).splice(3)
                    })
                    data.data = []
                    for (let i = 0; i < Math.ceil(stationData.length / data.row); i++) {
                        data.data.push(stationData.slice(i * data.row, (i + 1) * data.row))
                    }
                }

                onMounted(() => {
                    getData()
                    $.get("./api/get.php", { mode: "getRenewTime" }, (r) => {
                        setInterval(() => {
                            getData()
                            data.renewTime = 0
                        }, r * 1000)
                    })
                    setInterval(() => {
                        data.renewTime++
                    }, 1000)
                })

                return {
                    data,
                    getData,
                    processData
                }

            }
        }).mount("#app")

    </script>

</body>

</html>