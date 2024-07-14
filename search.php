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

        <!-- header -->

        <header>
            <nav class="navbar navbar-expand text-center bg-light p-3 shadow" style="height: 15vh;">
                <a href="./index.php">
                    <img src="./img/taipei101.png" alt="taipei101-logo"
                        style="height: 8vh; margin: 2vh 1vw; -webkit-user-drag: none;">
                </a>
                <h2 class="m-3 textHover" style="font-weight: 600;" onclick="location.href = './index.php'">台北 <span
                        style="color: var(--info);">101</span> 接駁專車系統</h2>
                <a href="./index.php" class="btn infoBtn m-3">&#9673; 路網圖</a>
                <a href="./search.php" class="btn infoBtn m-3 btn-info disabled">&#8981; 班次查詢</a>
                <a href="./manage.php" class="btn infoBtn m-3">&#9881; 系統管理</a>
            </nav>
        </header>

        <!-- main -->

        <main>

            <div class="container mx-auto shadow bg-light text-center"
                style="width: 70vw; height: 55vh; border-radius: 10px; position: relative; margin-top: 10vh;">

                <div id="alertErr" style="position: fixed; top: calc(15vh + 1em); right: 1em; font-weight: 900;"></div>

                <div class="rotateBorderLg" style="height: 50%; top: 1em;">

                    <div style="display: flex; justify-content: center; align-items: center;">
                        <h2 class="my-4 font-weight-bold textHover">&#8981; 班次查詢</h2>
                    </div>

                    <form action="#" method="post" @submit.prevent="searchBusSubmit">

                        <div class="form-row col-6 mx-auto">
                            <div class="col-8">
                                <input type="email" class="form-control form-control-lg" v-model="data.searchBus"
                                    placeholder="請輸入信箱" required>
                            </div>
                            <button type="submit" class="col-4 btn greenBtn">&#8981; 查詢</button>
                        </div>

                    </form>

                    <label class="mt-3" style="transform: scale(1.2);" v-show="data.displaySeveral">被分配到的接駁車車牌為{{
                        data.severalText }}</label>
                    <label class="mt-3" style="transform: scale(1.2);" v-show="data.noSeveral">目前尚未分配接駁車</label>
                    <label class="mt-3" style="transform: scale(1.2);" v-show="data.noParticipant">您不在參與者名單中</label>
                    <a href="./form.php" class="btn infoBtn m-3" target="_blank" v-show="data.notFill">前往意願調查表單</a>

                </div>

                <div class="clock"><i class="textHover">{{ data.time }}</i></div>

            </div>

        </main>

    </div>

    <!-- script -->

    <script src="./jquery/jquery.js"></script>
    <script src="./jquery/jquery-ui.min.js"></script>
    <script src="./jquery/vue3.global.js"></script>
    <script src="./bootstrap/bootstrap.js"></script>
    <script>

        const { createApp, ref, reactive, onMounted } = Vue
        createApp({
            setup() {

                const data = reactive({
                })

                const searchBusSubmit = () => {
                    if (/^[A-Za-z0-9]+@[A-Za-z0-9]+\.[A-Za-z0-9]+$/.test(data.searchBus)) {
                        $.get("./api/get.php", { mode: "getIsParticipant", email: data.searchBus }, (r) => {
                            if (r == 1) {
                                $.get("./api/get.php", { mode: "getIsSurvey", email: data.searchBus }, (re) => {
                                    if (re == 1) {
                                        $.get("./api/get.php", { mode: "getASurveySeveral", email: data.searchBus }, (res) => {
                                            if (res) {
                                                data.severalText = res
                                                data.displaySeveral = true
                                                data.noSeveral = false
                                            } else {
                                                data.displaySeveral = false
                                                data.noSeveral = true
                                            }
                                            data.notFill = false
                                        })
                                    } else {
                                        let alertId = "alert-" + new Date().getTime()
                                        $("#alertErr").append('<div id=' + alertId + ' class="alert alert-warning alert-dismissible border border-warning fade show text-center text-danger" style="width: 15vw;">' +
                                            '<span>您還沒填寫意願調查表單</span>' +
                                            '<button class="close" data-dismiss="alert">' +
                                            '<span>&#10008;</span>' +
                                            '</button>' +
                                            '</div>')

                                        $("#surveyForm").addClass("shake")
                                        setTimeout(() => {
                                            $("#surveyForm").removeClass("shake")
                                        }, 300)
                                        setTimeout(() => {
                                            $("#" + alertId).alert("close")
                                        }, 5000)
                                        data.no
                                        data.displaySeveral = false
                                        data.noSeveral = false
                                        data.notFill = true
                                    }
                                })
                            } else {
                                data.noParticipant = true
                                data.displaySeveral = false
                                data.noSeveral = false
                                data.notFill = false
                            }
                        })
                    } else {
                        alert("參與者信箱需包含「@」及至少1個「.」且符合email格式，例xxx@xxx.xxx")
                    }
                }

                const time = () => {
                    let now = new Date()
                    let hours = now.getHours().toString().padStart(2, "0")
                    let minutes = now.getMinutes().toString().padStart(2, "0")
                    let seconds = now.getSeconds().toString().padStart(2, "0")
                    data.time = `${hours}：${minutes}：${seconds}`
                }

                onMounted(() => {
                    time()
                    setInterval(() => {
                        time()
                    }, 1000)
                })

                return {
                    data,
                    searchBusSubmit,
                    time
                }

            }
        }).mount("#app")

    </script>

</body>

</html>