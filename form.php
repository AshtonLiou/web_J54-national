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
                        style="height: 8vh; margin: 2vh 1vw; user-select: none; -webkit-user-drag: none;">
                </a>
                <h2 class="m-3 textHover" style="font-weight: 600;" onclick="location.href = './index.php'">台北 <span
                        style="color: var(--info);">101</span> 接駁專車系統</h2>
                <a href="./index.php" class="btn infoBtn m-3">&#9673; 路網圖</a>
                <a href="./search.php" class="btn infoBtn m-3">&#8981; 班次查詢</a>
                <a href="./manage.php" class="btn infoBtn m-3">&#9881; 系統管理</a>
            </nav>
        </header>

        <!-- main -->

        <main>

            <!-- survey form -->

            <div class="container" style="margin-top: 15vh;">

                <div id="alertErr" style="position: fixed; top: calc(15vh + 1em); right: 1em; font-weight: 900;"></div>

                <form action="#" method="post" id="surveyForm"
                    class="w-75 mx-auto p-5 bg-light border border-dark shadow rounded"
                    @submit.prevent="surveyFormSubmit">

                    <h2 class="text-center mt-md-4 mt-0 font-weight-bold textHover">接駁意願調查表單</h2>

                    <div class="form-row mt-md-4 mt-3">
                        <div class="col-md col-12">
                            <input type="text" class="form-control form-control-lg" v-model="data.name"
                                placeholder="姓名" required>
                        </div>
                    </div>

                    <div class="form-row mt-md-4 mt-3">
                        <div class="col-md col-12 mt-md-0 mt-4">
                            <input type="email" class="form-control form-control-lg" v-model="data.email"
                                placeholder="信箱" required>
                        </div>
                    </div>

                    <hr>

                    <div class="form-row mt-4">
                        <button type="reset" class="btn grayBtn col-10 mx-auto"
                            style="background: var(--light) linear-gradient(var(--gray), var(--gray)) 0 0 / 0 100% no-repeat;"
                            @click="data.name = ''; data.email = '';">&#9851; 重整表單</button>
                    </div>

                    <div class="form-row mt-4">
                        <button type="submit" class="btn greenBtn col-10 mx-auto"
                            style="background: var(--light) linear-gradient(var(--green), var(--green)) 0 0 / 0 100% no-repeat;">&#8688; 送出</button>
                    </div>

                </form>

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

                const surveyFormSubmit = () => {
                    if ($("#surveyForm")[0].reportValidity()) {
                        if (/^[A-Za-z0-9]+@[A-Za-z0-9]+\.[A-Za-z0-9]+$/.test(data.email)) {
                            $.get("./api/get.php", { mode: "getIsParticipantFill", email: data.email }, (r) => {
                                if (r == 1) {
                                    $.get("./api/get.php", { mode: "getIsParticipant", email: data.email }, (re) => {
                                        if (re == 1) {
                                            if (localStorage.getItem("isOpenSurveyForm") == "false") {
                                                $.post("./api/post.php", { mode: "addNewSurvey", name: data.name, email: data.email }, () => {
                                                    alert("接駁意願調查成功送出")
                                                    location.reload()
                                                })
                                            } else {
                                                let alertId = "alert-" + new Date().getTime()
                                                $("#alertErr").append('<div id=' + alertId + ' class="alert alert-warning alert-dismissible border border-warning fade show text-center text-danger" style="width: 15vw;">' +
                                                    '<span>該表單目前不接受回應</span>' +
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
                                            }
                                        } else {
                                            let alertId = "alert-" + new Date().getTime()
                                            $("#alertErr").append('<div id=' + alertId + ' class="alert alert-danger alert-dismissible border border-danger fade show text-center text-danger" style="width: 15vw;">' +
                                                '<span>您不在參與者名單中</span>' +
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
                                        }
                                    })
                                } else {
                                    let alertId = new Date().getTime()
                                    $("#alertErr").append('<div id=' + alertId + ' class="alert alert-info alert-dismissible border border-info fade show text-center text-danger" style="width: 15vw;">' +
                                        '<span>您已經參與過意見調查</span>' +
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
                                }
                            })
                        } else {
                            alert("參與者信箱需包含「@」及至少1個「.」且符合email格式，例xxx@xxx.xxx")
                        }
                    }
                }

                onMounted(() => {

                })

                return {
                    data,
                    surveyFormSubmit
                }

            }
        }).mount("#app")

    </script>

</body>

</html>