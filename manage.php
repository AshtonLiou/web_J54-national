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
            <nav class="navbar navbar-expand bg-light p-3 shadow" style="height: 15vh;">
                <a href="./index.php">
                    <img src="./img/taipei101.png" alt="taipei101-logo"
                        style="height: 8vh; margin: 2vh 1vw; -webkit-user-drag: none;">
                </a>
                <h2 class="m-3 textHover" style="font-weight: 600;" onclick="location.href = './index.php'">台北 <span
                        style="color: var(--info);">101</span> 接駁專車系統</h2>
                <a href="./index.php" class="btn infoBtn m-3">&#9673; 路網圖</a>
                <a href="./search.php" class="btn infoBtn m-3">&#8981; 班次查詢</a>
                <a href="./manage.php" class="btn infoBtn m-3 btn-info disabled">&#9881; 系統管理</a>
                <a href="./form.php" class="btn grayBtn ml-auto" v-show="data.isLogin" target="_blank">前往表單</a>
                <button class="btn dangerBtn ml-3" v-show="data.isLogin" @click="logout">&#8688; 登出</button>
            </nav>
        </header>

        <!-- main -->

        <main>

            <!-- login form -->

            <div class="container" style="margin-top: 15vh;" v-show="!data.isLogin">

                <div id="alertError" style="position: fixed; top: calc(15vh + 1em); right: 1em; font-weight: 900;">
                </div>

                <form action="#" id="loginForm" class="w-75 mx-auto p-5 bg-light border border-dark shadow rounded"
                    @submit.prevent="login">

                    <h2 class="text-center mt-md-4 mt-0 font-weight-bold textHover">網站管理-登入</h2>

                    <div class="form-row mt-md-4 mt-3">
                        <div class="col-md col-12">
                            <input type="text" class="form-control form-control-lg" v-model="data.acc" placeholder="帳號"
                                required>
                        </div>
                        <div class="col-md col-12 mt-md-0 mt-4">
                            <input type="password" class="form-control form-control-lg" v-model="data.pw"
                                placeholder="密碼" required>
                        </div>
                    </div>

                    <div class="form-row mt-md-5 mt-4 align-items-center">
                        <div class="col-md-6 col-12">
                            <input type="text" class="form-control form-control-lg" v-model="data.verification"
                                placeholder="驗證碼" required>
                        </div>
                        <span id="captcha" class="col-md-2 col-8 text-center mx-auto mt-md-0 mt-4">{{ data.captcha
                            }}</span>
                        <button type="button" class="btn grayBtn col-md-3 col-10 mx-md-0 mx-auto mt-md-0 mt-4"
                            style="background: var(--light) linear-gradient(var(--gray), var(--gray)) 0 0 / 0 100% no-repeat;"
                            @click="reGenerate">重新產生驗證碼</button>
                    </div>

                    <hr>

                    <div class="form-row mt-4">
                        <button type="reset" class="btn grayBtn col-10 mx-auto"
                            style="background: var(--light) linear-gradient(var(--gray), var(--gray)) 0 0 / 0 100% no-repeat;"
                            @click="data.acc = ''; data.pw = ''; data.verification = '';">&#9851; 重整表單</button>
                    </div>

                    <div class="form-row mt-4">
                        <button type="submit" class="btn greenBtn col-10 mx-auto"
                            style="background: var(--light) linear-gradient(var(--green), var(--green)) 0 0 / 0 100% no-repeat;">&#8688;
                            登入</button>
                    </div>

                </form>

            </div>

            <!-- manage -->

            <div id="slider" class="carousel slide" v-show="data.isLogin">

                <!-- manage navbar -->

                <div class="container mt-5 p-2 bg-light text-center shadow rounded-pill" style="width: 45vw;">
                    <div class="rounded-pill p-2"
                        style="position: relative; display: flex; justify-content: space-around; overflow: hidden;">
                        <div class="tab h-100 rounded-pill" :style="{ 'left': data.left }"></div>
                        <h5 class="w-100 mx-2 my-1 text-dark font-weight-bold" style="cursor: pointer; z-index: 100;"
                            data-target="#slider" data-slide-to="0" @click="data.left = '0%'">接駁車管理</h5>
                        <h5 class="w-100 mx-2 my-1 text-dark font-weight-bold" style="cursor: pointer; z-index: 100;"
                            data-target="#slider" data-slide-to="1" @click="data.left = 'calc(100% / 3)'">站點管理</h5>
                        <h5 class="w-100 mx-2 my-1 text-dark font-weight-bold" style="cursor: pointer; z-index: 100;"
                            data-target="#slider" data-slide-to="2" @click="data.left = 'calc(100% / 3 * 2)'">表單設定</h5>
                    </div>
                </div>

                <!-- manage tables -->

                <div class="carousel-inner mx-auto mt-4 shadow" style="width: 70vw; height: 60vh; border-radius: 10px;">

                    <!-- bus manage -->

                    <div class="carousel-item w-100 h-100 bg-light p-4 active" style="overflow-y: auto;">

                        <div style="display: flex; justify-content: center; align-items: center;">
                            <div class="rotateBorder py-3"
                                style="display: flex; justify-content: center; align-items: center;">
                                <button class="btn btn-sm greenBtn" style="visibility: hidden;">&#10010; 新增</button>
                                <label for="renewTime"
                                    style="display: flex; justify-content: center; align-items: center;">路網圖每
                                    <input type="number" id="renewTime" class="form-control form-control-sm mx-2"
                                        style="width: 4em;" min="1" v-model="data.renewTime" @input="renewTime">秒更新
                                </label>
                                <h2 class="mx-4 my-2 font-weight-bold textHover">接駁車管理</h2>
                                <button class="btn btn-sm greenBtn" @click="data.addNewBus = []"
                                    data-target="#addNewBusModal" data-toggle="modal">&#10010; 新增</button>
                                <label
                                    style="display: flex; justify-content: center; align-items: center; visibility: hidden;">路網圖每
                                    <input type="number" class="form-control form-control-sm mx-2"
                                        style="width: 4em;">秒更新
                                </label>
                            </div>
                        </div>

                        <table class="container table table-striped table-hover mt-2" style="overflow: hidden;">
                            <thead class="thead-dark">
                                <tr>
                                    <th>車牌</th>
                                    <th>已行駛時間(分鐘)</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in data.busData" :id="JSON.stringify(item)"
                                    :class="{ 'table-danger': item.checkDelete, 'confirmDeleteAnimation': item.confirmDeleteAnimation }">
                                    <td>{{ item.name }}</td>
                                    <td>{{ item.time }}分鐘</td>
                                    <td class="text-left" style="width: 20em;">
                                        <button class="btn grayBtn mx-1" style="width: 8em;"
                                            @click="editBusRecord(item)" v-show="!item.checkDelete"
                                            data-target="#editBusModal" data-toggle="modal">&#9998; 編輯</button>
                                        <button class="btn dangerBtn mx-1" style="width: 8em;"
                                            @click="deleteBusRecord(item.id)" v-show="!item.checkDelete">&#10008;
                                            刪除</button>
                                        <button class="btn dangerBtn mx-1" style="width: 8em;"
                                            @click="confirmDeleteBus(item.id)" v-show="item.checkDelete">&#10004;
                                            確定刪除</button>
                                        <button class="btn greenBtn mx-1" style="width: 8em;"
                                            @click="unDeleteBus(item.id)" v-show="item.checkDelete">&#10008;
                                            取消刪除</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- add new bus modal -->

                        <div id="addNewBusModal" class="modal fade">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">

                                        <h5 class="modal-title font-weight-bold">&#10010; 新增接駁車</h5>
                                        <button class="close" data-dismiss="modal">
                                            <span>&#10008;</span>
                                        </button>

                                    </div>
                                    <div class="modal-body">

                                        <form active="#" id="addNewBusForm" @submit.prevent="addNewBusSubmit">

                                            <div class="form-row">
                                                <div class="col">
                                                    <label for="addNewBusName">車牌</label>
                                                    <input type="text" id="addNewBusName" class="form-control"
                                                        v-model="data.addNewBus.name" placeholder="請輸入車牌" required>
                                                </div>
                                            </div>

                                            <div class="form-row mt-4">
                                                <div class="col">
                                                    <label for="addNewBusTime">已行駛時間(分鐘)</label>
                                                    <input type="number" id="addNewBusTime" class="form-control" min="0"
                                                        v-model="data.addNewBus.time" placeholder="請輸入已行駛時間(分鐘)"
                                                        required>
                                                </div>
                                            </div>

                                            <div class="form-row mt-4">
                                                <button type="button" class="btn grayBtn col-5 mx-auto"
                                                    data-dismiss="modal">&#10008; 關閉視窗</button>
                                                <button type="submit" class="btn greenBtn col-5 mx-auto">&#10004;
                                                    新增</button>
                                            </div>

                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- edit bus modal -->

                        <div id="editBusModal" class="modal fade">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">

                                        <h5 class="modal-title font-weight-bold">&#9998; 修改「{{ data.editBus.title }}」接駁車
                                        </h5>
                                        <button class="close" data-dismiss="modal">
                                            <span>&#10008;</span>
                                        </button>

                                    </div>
                                    <div class="modal-body">

                                        <form active="#" id="editBusForm" @submit.prevent="editBusSubmit">

                                            <div class="form-row">
                                                <div class="col">
                                                    <label for="editBusTime">已行駛時間(分鐘)</label>
                                                    <input type="number" id="editBusTime" class="form-control" min="0"
                                                        v-model="data.editBus.time" placeholder="請輸入已行駛時間(分鐘)" required>
                                                </div>
                                            </div>

                                            <div class="form-row mt-4">
                                                <button type="button" class="btn grayBtn col-5 mx-auto"
                                                    data-dismiss="modal">&#10008; 關閉視窗</button>
                                                <button type="submit" class="btn greenBtn col-5 mx-auto">&#10004;
                                                    修改</button>
                                            </div>

                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- station manage -->

                    <div class="carousel-item w-100 h-100 bg-light p-4" style="overflow-y: auto;">

                        <div style="display: flex; justify-content: center; align-items: center;">
                            <div class="rotateBorder py-3"
                                style="display: flex; justify-content: center; align-items: center;">
                                <button class="btn btn-sm greenBtn" style="visibility: hidden;">&#10010; 新增</button>
                                <label for="renewRow"
                                    style="display: flex; justify-content: center; align-items: center;">地圖每列顯示
                                    <input type="number" id="renewRow" class="form-control form-control-sm mx-2"
                                        style="width: 4em;" min="1" v-model="data.rowNum" @input="renewRow">站
                                </label>
                                <h2 class="mx-4 my-2 font-weight-bold textHover">站點管理</h2>
                                <button class="btn btn-sm greenBtn" data-target="#addNewStationModal"
                                    data-toggle="modal" @click="data.addNewStation = []">&#10010;
                                    新增</button>
                                <label
                                    style="display: flex; justify-content: center; align-items: center; visibility: hidden;">地圖每列顯示
                                    <input type="number" class="form-control form-control-sm mx-2" style="width: 4em;"
                                        min="1">站
                                </label>
                            </div>
                        </div>

                        <table class="container table table-striped table-hover mt-2" style="overflow: hidden;">
                            <thead class="thead-dark">
                                <tr>
                                    <th>站點名稱(拖曳改變順序)</th>
                                    <th>行駛時間(分鐘)</th>
                                    <th>停留時間(分鐘)</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody id="stationTbody">
                                <tr v-for="item in data.stationData" :id="JSON.stringify(item)"
                                    :class="{ 'table-danger': item.checkDelete, 'confirmDeleteAnimation': item.confirmDeleteAnimation }">
                                    <td>{{ item.name }}</td>
                                    <td>{{ item.time }}分鐘</td>
                                    <td>{{ item.wait }}分鐘</td>
                                    <td class="text-left" style="width: 20em;">
                                        <button class="btn grayBtn mx-1" style="width: 8em;"
                                            @click="editStationRecord(item)" v-show="!item.checkDelete"
                                            data-target="#editStationModal" data-toggle="modal">&#9998; 編輯</button>
                                        <button class="btn dangerBtn mx-1" style="width: 8em;"
                                            @click="deleteStationRecord(item.id)" v-show="!item.checkDelete">&#10008;
                                            刪除</button>
                                        <button class="btn dangerBtn mx-1" style="width: 8em;"
                                            @click="confirmDeleteStation(item.id)" v-show="item.checkDelete">&#10004;
                                            確定刪除</button>
                                        <button class="btn greenBtn mx-1" style="width: 8em;"
                                            @click="unDeleteStation(item.id)" v-show="item.checkDelete">&#10008;
                                            取消刪除</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- add new station modal -->

                        <div id="addNewStationModal" class="modal fade">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">

                                        <h5 class="modal-title font-weight-bold">&#10010; 新增站點</h5>
                                        <button class="close" data-dismiss="modal">
                                            <span>&#10008;</span>
                                        </button>

                                    </div>
                                    <div class="modal-body">

                                        <form active="#" id="addNewStationForm" @submit.prevent="addNewStationSubmit">

                                            <div class="form-row">
                                                <div class="col">
                                                    <label for="addNewStationName">站點名稱</label>
                                                    <input type="text" id="addNewStationName" class="form-control"
                                                        v-model="data.addNewStation.name" placeholder="請輸入站點名稱"
                                                        required>
                                                </div>
                                            </div>

                                            <div class="form-row mt-4">
                                                <div class="col">
                                                    <label for="addNewStationTime">行駛時間(分鐘)</label>
                                                    <input type="number" id="addNewStationTime" class="form-control"
                                                        min="0" v-model="data.addNewStation.time"
                                                        placeholder="請輸入行駛時間(分鐘)" required>
                                                </div>
                                            </div>

                                            <div class="form-row mt-4">
                                                <div class="col">
                                                    <label for="addNewStationWait">停留時間(分鐘)</label>
                                                    <input type="number" id="addNewStationWait" class="form-control"
                                                        min="0" v-model="data.addNewStation.wait" placeholder="停留時間(分鐘)"
                                                        required>
                                                </div>
                                            </div>

                                            <div class="form-row mt-4">
                                                <button type="button" class="btn grayBtn col-5 mx-auto"
                                                    data-dismiss="modal">&#10008; 關閉視窗</button>
                                                <button type="submit" class="btn greenBtn col-5 mx-auto">&#10004;
                                                    新增</button>
                                            </div>

                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- edit station modal -->

                        <div id="editStationModal" class="modal fade">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">

                                        <h5 class="modal-title font-weight-bold">&#9998; 修改「{{ data.editStation.title
                                            }}」
                                        </h5>
                                        <button class="close" data-dismiss="modal">
                                            <span>&#10008;</span>
                                        </button>

                                    </div>
                                    <div class="modal-body">

                                        <form active="#" id="editStationForm" @submit.prevent="editStationSubmit">

                                            <div class="form-row">
                                                <div class="col">
                                                    <label for="editStationTime">行駛時間(分鐘)</label>
                                                    <input type="number" id="editStationTime" class="form-control"
                                                        min="0" v-model="data.editStation.time"
                                                        placeholder="請輸入行駛時間(分鐘)" required>
                                                </div>
                                            </div>

                                            <div class="form-row mt-4">
                                                <div class="col">
                                                    <label for="editStationWait">停留時間(分鐘)</label>
                                                    <input type="number" id="editStationWait" class="form-control"
                                                        min="0" v-model="data.editStation.wait"
                                                        placeholder="請輸入停留時間(分鐘)" required>
                                                </div>
                                            </div>

                                            <div class="form-row mt-4">
                                                <button type="button" class="btn grayBtn col-5 mx-auto"
                                                    data-dismiss="modal">&#10008; 關閉視窗</button>
                                                <button type="submit" class="btn greenBtn col-5 mx-auto">&#10004;
                                                    修改</button>
                                            </div>

                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- form manage -->

                    <div class="carousel-item w-100 h-100 bg-light p-4" style="overflow-y: auto;">

                        <!-- form manage navbar -->

                        <div style="display: flex; justify-content: center; align-items: center;">
                            <div class="rotateBorder w-100"
                                style="display: flex; justify-content: center; align-items: center;">
                                <div class="custom-control custom-switch col-3"
                                    style="display: flex; justify-content: center; align-items: center;">
                                    <input type="checkbox" id="switch" class="custom-control-input"
                                        v-model="data.isOpenSurveyForm" @input="changeSwitch">
                                    <label for="switch" class="custom-control-label font-weight-bold"
                                        style="transform: scale(1.7); border-radius: 5px; font-size: .6em;">是否啟用表單</label>
                                </div>
                                <select class="custom-select col-2 font-weight-bold" v-model="data.formMode"
                                    @change="changeFormMode">
                                    <optgroup label="模式">
                                        <option :value="1">&#9881; 參與者名單</option>
                                        <option :value="2">&#9881; 意願調查結果</option>
                                    </optgroup>
                                </select>
                                <h2 class="mx-4 my-4 font-weight-bold textHover">表單設定</h2>
                                <div class="form-row col-5" v-show="data.formMode == 1">
                                    <form action="#" class="col d-flex" @submit.prevent="addNewParticipantSubmit">

                                        <div class="col-8">
                                            <input type="email" class="form-control" v-model="data.addNewParticipant"
                                                placeholder="設定參與者信箱" required>
                                        </div>
                                        <button type="submit" class="col-4 btn btn-sm greenBtn">&#10010;
                                            新增</button>

                                    </form>
                                </div>
                                <div class="form-row col-5" v-show="data.formMode == 2">
                                    <label class="m-0">目前需派遣{{ data.severalBus }}輛接駁車</label>
                                    <button class="btn btn-sm greenBtn ml-3" @click="produceBus">&#10010; 產生接駁車</button>
                                </div>
                            </div>
                        </div>

                        <table class="container table table-striped table-hover mt-2" style="overflow: hidden;"
                            v-show="data.formMode == 1">
                            <thead class="thead-dark">
                                <tr>
                                    <th>參與者名單</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in data.participantData" :id="item.id"
                                    :class="{ 'table-danger': item.checkDelete, 'confirmDeleteAnimation': item.confirmDeleteAnimation }">
                                    <td>{{ item.email }}</td>
                                    <td style="width: 20em;" class="text-left">
                                        <button class="btn grayBtn mx-1" style="width: 8em;"
                                            @click="editParticipantRecord(item)" v-show="!item.checkDelete"
                                            data-target="#editParticipantModal" data-toggle="modal">&#9998; 編輯</button>
                                        <button class="btn dangerBtn mx-1" style="width: 8em;"
                                            @click="deleteParticipantRecord(item.id)"
                                            v-show="!item.checkDelete">&#10008;
                                            刪除</button>
                                        <button class="btn dangerBtn mx-1" style="width: 8em;"
                                            @click="confirmDeleteParticipant(item.id)"
                                            v-show="item.checkDelete">&#10004;
                                            確定刪除</button>
                                        <button class="btn greenBtn mx-1" style="width: 8em;"
                                            @click="unDeleteParticipant(item.id)" v-show="item.checkDelete">&#10008;
                                            取消刪除</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div id="editParticipantModal" class="modal fade">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">

                                        <h5 class="modal-title font-weight-bold">&#9998; 修改「{{
                                            data.editParticipant.title }}」信箱
                                        </h5>
                                        <button class="close" data-dismiss="modal">
                                            <span>&#10008;</span>
                                        </button>

                                    </div>
                                    <div class="modal-body">
                                        <form active="#" id="editParticipantForm"
                                            @submit.prevent="editParticipantSubmit">

                                            <div class="form-row">
                                                <div class="col">
                                                    <label for="editParticipantEmail">參與者信箱</label>
                                                    <input type="email" id="editParticipantEmail" class="form-control"
                                                        v-model="data.editParticipant.email" placeholder="請輸入參與者信箱"
                                                        required>
                                                </div>
                                            </div>

                                            <div class="form-row mt-4">
                                                <button type="button" class="btn grayBtn col-5 mx-auto"
                                                    data-dismiss="modal">&#10008; 關閉視窗</button>
                                                <button type="submit" class="btn greenBtn col-5 mx-auto">&#10004;
                                                    修改</button>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- survey manage -->

                        <table class="container table table-striped table-hover mt-2" style="overflow: hidden;"
                            v-show="data.formMode == 2">
                            <thead class="thead-dark">
                                <tr>
                                    <th>參與者姓名</th>
                                    <th>參與者信箱</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in data.surveyData" :id="item.id"
                                    :class="{ 'table-danger': item.checkDelete, 'confirmDeleteAnimation': item.confirmDeleteAnimation }">
                                    <td>{{ item.name }}</td>
                                    <td>{{ item.email }}</td>
                                    <td style="width: 20em;" class="text-left">
                                        <button class="btn grayBtn mx-1" style="width: 8em;"
                                            @click="editSurveyRecord(item)" v-show="!item.checkDelete"
                                            data-target="#editSurveyModal" data-toggle="modal">&#9998; 編輯</button>
                                        <button class="btn dangerBtn mx-1" style="width: 8em;"
                                            @click="deleteSurveyRecord(item.id)" v-show="!item.checkDelete">&#10008;
                                            刪除</button>
                                        <button class="btn dangerBtn mx-1" style="width: 8em;"
                                            @click="confirmDeleteSurvey(item.id)" v-show="item.checkDelete">&#10004;
                                            確定刪除</button>
                                        <button class="btn greenBtn mx-1" style="width: 8em;"
                                            @click="unDeleteSurvey(item.id)" v-show="item.checkDelete">&#10008;
                                            取消刪除</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- edit survey modal -->

                        <div id="editSurveyModal" class="modal fade">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">

                                        <h5 class="modal-title font-weight-bold">&#9998; 修改「{{ data.editSurvey.title
                                            }}」姓名
                                        </h5>
                                        <button class="close" data-dismiss="modal">
                                            <span>&#10008;</span>
                                        </button>

                                    </div>
                                    <div class="modal-body">

                                        <form active="#" id="editSurveyForm" @submit.prevent="editSurveySubmit">

                                            <div class="form-row">
                                                <div class="col">
                                                    <label for="editSurveyName">參與者姓名</label>
                                                    <input type="text" id="editSurveyName" class="form-control"
                                                        v-model="data.editSurvey.name" placeholder="請輸入參與者姓名" required>
                                                </div>
                                            </div>

                                            <div class="form-row mt-4">
                                                <button type="button" class="btn grayBtn col-5 mx-auto"
                                                    data-dismiss="modal">&#10008; 關閉視窗</button>
                                                <button type="submit" class="btn greenBtn col-5 mx-auto">&#10004;
                                                    修改</button>
                                            </div>

                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <h2 class="textHover"
                            style="font-weight: 900; font-size: 3em; color: #ccc; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-wrap: nowrap;"
                            v-show="data.noSurveyData && data.formMode == 2">尚未收到新的意願調查結果</h2>

                        <h2 class="textHover"
                            style="font-weight: 900; font-size: 3em; color: #ccc; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-wrap: nowrap;"
                            v-show="data.noParticipantData && data.formMode == 1">尚未設定參與者信箱</h2>

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
                    severalBus: 0,
                    switch: false,
                    addNewBus: [],
                    editBus: [],
                    addNewStation: [],
                    editStation: [],
                    editParticipant: [],
                    editSurvey: []
                })

                const reGenerate = () => {
                    data.captcha = Math.floor(Math.random() * 8999 + 1000)
                }

                const login = () => {
                    if (data.verification == data.captcha) {
                        $.get("./api/get.php", { mode: "getLoginData", acc: data.acc, pw: data.pw }, (r) => {
                            if (r == 1) {
                                localStorage.setItem("isLogin", "true")
                                data.isLogin = true
                                data.acc = ""
                                data.pw = ""
                                data.verification = ""
                                $(".alert").alert("close")
                            } else {
                                let alertId = "alert-" + new Date().getTime()
                                $("#alertError").append('<div id=' + alertId + ' class="alert alert-warning alert-dismissible border border-warning fade show text-center text-danger" style="width: 15vw;">' +
                                    '<span>帳號或密碼錯誤，哈哈可憐</span>' +
                                    '<button class="close" data-dismiss="alert">' +
                                    '<span>&#10008;</span>' +
                                    '</button>' +
                                    '</div>')

                                $("#loginForm").addClass("shake")
                                setTimeout(() => {
                                    $("#loginForm").removeClass("shake")
                                }, 300)
                                setTimeout(() => {
                                    $("#" + alertId).alert("close")
                                }, 5000)
                            }
                        })
                    } else {
                        let alertId = "alert-" + new Date().getTime()
                        $("#alertError").append('<div id=' + alertId + ' class="alert alert-danger alert-dismissible border border-danger fade show text-center text-danger" style="width: 15vw;">' +
                            '<span>驗證碼錯誤，哈哈笑死</span>' +
                            '<button class="close" data-dismiss="alert">' +
                            '<span>&#10008;</span>' +
                            '</button>' +
                            '</div>')

                        $("#loginForm").addClass("shake")
                        setTimeout(() => {
                            $("#loginForm").removeClass("shake")
                        }, 300)
                        setTimeout(() => {
                            $("#" + alertId).alert("close")
                        }, 5000)
                    }
                }

                const logout = () => {
                    localStorage.setItem("isLogin", "false")
                    data.isLogin = false
                }

                const getBusData = () => {
                    $.getJSON("./api/get.php", { mode: "getBusData" }, (r) => {
                        data.busData = r
                    })
                }

                const addNewBusSubmit = () => {
                    $.post("./api/post.php", { mode: "addNewBus", name: data.addNewBus.name, time: data.addNewBus.time }, () => {
                        getBusData()
                        $("#addNewBusModal").modal("hide")
                    })
                }

                const editBusRecord = (item) => {
                    data.editBus.id = item.id
                    data.editBus.title = item.name
                    data.editBus.time = item.time
                }

                const editBusSubmit = () => {
                    $.post("./api/post.php", { mode: "editBusData", id: data.editBus.id, time: data.editBus.time }, () => {
                        getBusData()
                        $("#editBusModal").modal("hide")
                    })
                }

                const deleteBusRecord = (id) => {
                    data.busData.find(item => item.id == id).checkDelete = true
                }

                const unDeleteBus = (id) => {
                    data.busData.find(item => item.id == id).checkDelete = false
                }

                const confirmDeleteBus = (id) => {
                    $.post("./api/post.php", { mode: "deleteBus", id }, () => {
                        data.busData.find(item => item.id == id).confirmDeleteAnimation = true
                        setTimeout(() => {
                            getBusData()
                        }, 200)
                    })
                }

                const getStationData = () => {
                    $.getJSON("./api/get.php", { mode: "getStationData" }, (r) => {
                        data.stationData = r
                    })
                }

                const addNewStationSubmit = () => {
                    $.post("./api/post.php", { mode: "addNewStation", name: data.addNewStation.name, time: data.addNewStation.time, wait: data.addNewStation.wait }, () => {
                        getStationData()
                        $("#addNewStationModal").modal("hide")
                    })
                }

                const editStationRecord = (item) => {
                    data.editStation.id = item.id
                    data.editStation.title = item.name
                    data.editStation.time = item.time
                    data.editStation.wait = item.wait
                }

                const editStationSubmit = () => {
                    $.post("./api/post.php", { mode: "editStationData", id: data.editStation.id, time: data.editStation.time, wait: data.editStation.wait }, () => {
                        getStationData()
                        $("#editStationModal").modal("hide")
                    })
                }

                const deleteStationRecord = (id) => {
                    data.stationData.find(item => item.id == id).checkDelete = true
                }

                const unDeleteStation = (id) => {
                    data.stationData.find(item => item.id == id).checkDelete = false
                }

                const confirmDeleteStation = (id) => {
                    $.post("./api/post.php", { mode: "deleteStation", id }, () => {
                        data.stationData.find(item => item.id == id).confirmDeleteAnimation = true
                        setTimeout(() => {
                            getStationData()
                        }, 200)
                    })
                }

                const renewRow = () => {
                    $.post("./api/post.php", { mode: "renewRow", rowNum: data.rowNum })
                }

                const renewTime = () => {
                    $.post("./api/post.php", { mode: "renewTime", renewTime: data.renewTime })
                }

                const getSurveyData = () => {
                    $.getJSON("./api/get.php", { mode: "getSurveyData" }, (r) => {
                        data.surveyData = r
                        data.surveyData == "" ? data.noSurveyData = true : data.noSurveyData = false
                    })
                }

                const changeSwitch = () => {
                    localStorage.setItem("isOpenSurveyForm", data.isOpenSurveyForm)
                }

                const addNewParticipantSubmit = () => {
                    if (/^[A-Za-z0-9]+@[A-Za-z0-9]+\.[A-Za-z0-9]+$/.test(data.addNewParticipant)) {
                        $.get("./api/get.php", { mode: "getIsParticipant", email: data.addNewParticipant }, (r) => {
                            if (r == 1) {
                                alert("該參與者已參與意願調查")
                            } else {
                                $.post("./api/post.php", { mode: "addNewParticipant", email: data.addNewParticipant }, () => {
                                    data.addNewParticipant = ""
                                    getParticipantData()
                                    alert("成功新增信箱")
                                })
                            }
                        })
                    } else {
                        alert("參與者信箱需包含「@」及至少1個「.」且符合email格式，例xxx@xxx.xxx")
                    }
                }

                const editSurveyRecord = (item) => {
                    data.editSurvey.id = item.id
                    data.editSurvey.title = item.email
                    data.editSurvey.name = item.name
                }

                const editSurveySubmit = () => {
                    $.post("./api/post.php", { mode: "editSurveyData", id: data.editSurvey.id, name: data.editSurvey.name }, () => {
                        getSurveyData()
                        $("#editSurveyModal").modal("hide")
                    })
                }

                const deleteSurveyRecord = (id) => {
                    data.surveyData.find(item => item.id == id).checkDelete = true
                }

                const unDeleteSurvey = (id) => {
                    data.surveyData.find(item => item.id == id).checkDelete = false
                }

                const confirmDeleteSurvey = (id) => {
                    $.post("./api/post.php", { mode: "deleteSurvey", id }, () => {
                        data.surveyData.find(item => item.id == id).confirmDeleteAnimation = true
                        setTimeout(() => {
                            getSurveyData()
                        }, 200)
                    })
                }

                const produceBus = () => {
                    $.post("./api/post.php", { mode: "produceBus" }, () => {
                        data.surveyData.forEach(item => item.confirmDeleteAnimation = true)
                        setTimeout(() => {
                            getSurveyData()
                        }, 200)
                        localStorage.setItem("isOpenSurveyForm", "true")
                        data.isOpenSurveyForm = false
                    })
                }

                const getParticipantData = () => {
                    $.getJSON("./api/get.php", { mode: "getParticipantData" }, (r) => {
                        data.participantData = r
                        data.participantData == "" ? data.noParticipantData = true : data.noParticipantData = false
                    })
                }

                const editParticipantRecord = (item) => {
                    data.editParticipant.id = item.id
                    data.editParticipant.title = item.email
                    data.editParticipant.email = item.email
                }

                const editParticipantSubmit = () => {
                    if (/^[A-Za-z0-9]+@[A-Za-z0-9]+\.[A-Za-z0-9]+$/.test(data.editParticipant.email)) {
                        $.post("./api/post.php", { mode: "editParticipantData", id: data.editParticipant.id, email: data.editParticipant.email }, () => {
                            getParticipantData()
                            $("#editParticipantModal").modal("hide")
                        })
                    } else {
                        alert("參與者信箱需包含「@」及至少1個「.」且符合email格式，例xxx@xxx.xxx")
                    }
                }

                const deleteParticipantRecord = (id) => {
                    data.participantData.find(item => item.id == id).checkDelete = true
                }

                const unDeleteParticipant = (id) => {
                    data.participantData.find(item => item.id == id).checkDelete = false
                }

                const confirmDeleteParticipant = (id) => {
                    $.post("./api/post.php", { mode: "deleteParticipant", id }, () => {
                        data.participantData.find(item => item.id == id).confirmDeleteAnimation = true
                        setTimeout(() => {
                            getParticipantData()
                        }, 200)
                    })
                }

                const changeFormMode = () => {
                    sessionStorage.setItem("formMode", String(data.formMode))
                }

                onMounted(() => {
                    data.isLogin = localStorage.getItem("isLogin") == "true"
                    data.isOpenSurveyForm = localStorage.getItem("isOpenSurveyForm") == "false"
                    data.formMode = sessionStorage.getItem("formMode") ?? 1
                    data.left = 100 / 3 * sessionStorage.getItem("carouselTo") + "%"
                    reGenerate()
                    getBusData()
                    getStationData()
                    getParticipantData()
                    getSurveyData()
                    $.get("./api/get.php", { mode: "getRowNum" }, (r) => {
                        data.rowNum = r
                    })
                    $.get("./api/get.php", { mode: "getRenewTime" }, (r) => {
                        data.renewTime = r
                    })
                    $.get("./api/get.php", { mode: "getSurveySeveral" }, (r) => {
                        data.severalBus = r
                        console.log(data.severalBus)
                    })
                    $("#slider").carousel(Number(sessionStorage.getItem("carouselTo"))).carousel("pause").on("slide.bs.carousel", (e) => {
                        sessionStorage.setItem("carouselTo", String($(e.relatedTarget).index()))
                    })
                    $("#stationTbody").sortable({
                        axis: "y",
                        placeholder: "bg-info",
                        cursor: "n-resize",
                        helper: (e, ui) => {
                            ui.children().each(function () {
                                $(this).width($(this).width())
                            })
                            return ui
                        },
                        update: async () => {
                            let truncateTable = true
                            let sortData = $("#stationTbody").sortable("toArray").map(item => JSON.parse(item))
                            for (let i = 0; i < sortData.length; i++) {
                                await $.post("./api/post.php", { mode: "sortStationTable", truncateTable, name: sortData[i].name, time: sortData[i].time, wait: sortData[i].wait })
                                truncateTable = false
                            }
                        }
                    })
                })

                return {
                    data,
                    reGenerate,
                    login,
                    logout,
                    getBusData,
                    addNewBusSubmit,
                    editBusRecord,
                    editBusSubmit,
                    deleteBusRecord,
                    unDeleteBus,
                    confirmDeleteBus,
                    getStationData,
                    addNewStationSubmit,
                    editStationRecord,
                    editStationSubmit,
                    deleteStationRecord,
                    unDeleteStation,
                    confirmDeleteStation,
                    renewRow,
                    renewTime,
                    changeSwitch,
                    changeSwitch,
                    addNewParticipantSubmit,
                    getSurveyData,
                    editSurveyRecord,
                    editSurveySubmit,
                    deleteSurveyRecord,
                    unDeleteSurvey,
                    confirmDeleteSurvey,
                    produceBus,
                    getParticipantData,
                    editParticipantRecord,
                    deleteParticipantRecord,
                    unDeleteParticipant,
                    editParticipantSubmit,
                    confirmDeleteParticipant,
                    changeFormMode
                }

            }
        }).mount("#app")

    </script>

</body>

</html>