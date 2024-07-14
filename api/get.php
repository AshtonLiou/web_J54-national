<?php

include_once "./db.php";

switch ($_GET["mode"]) {
    case "getLoginData":
        $result = $conn->query("SELECT * FROM `admin` WHERE `acc` = '{$_GET['acc']}' AND `pw` = '{$_GET['pw']}'")->fetchAll();
        if (empty($result)) {
            echo 0;
        } else {
            echo 1;
        }
        break;

    case "getRowNum":
        $result = $conn->query("SELECT `rowNum` FROM `admin`")->fetch();
        echo $result["rowNum"];
        break;

    case "getRenewTime":
        $result = $conn->query("SELECT `renewTime` FROM `admin`")->fetch();
        echo $result["renewTime"];
        break;

    case "getBusData":
        $result = $conn->query("SELECT * FROM `bus`")->fetchAll();
        echo json_encode($result);
        break;

    case "getStationData":
        $result = $conn->query("SELECT * FROM `station`")->fetchAll();
        echo json_encode($result);
        break;

    // project 3

    case "getParticipantData":
        $result = $conn->query("SELECT * FROM `participant`")->fetchAll();
        echo json_encode($result);
        break;

    case "getIsParticipantFill":
        $result = $conn->query("SELECT * FROM `survey` WHERE `email` = '{$_GET['email']}'")->fetchAll();
        if (empty($result)) {
            echo 1;
        } else {
            echo 0;
        }
        break;

   case "getIsParticipant":
       $result = $conn->query("SELECT * FROM `participant` WHERE `email` = '{$_GET['email']}'")->fetchAll();
       if (empty($result)) {
           echo 0;
       } else {
           echo 1;
       }
       break;

    case "getSurveyData":
        $result = $conn->query("SELECT * FROM `survey` WHERE `several` = ''")->fetchAll();
        echo json_encode($result);
        break;

    case "getSurveySeveral":
        $result = $conn->query("SELECT COUNT(*) FROM `survey` WHERE `several` = ''")->fetchColumn();
        echo ceil($result / 50);
        break;

    case "getASurveySeveral":
        $result = $conn->query("SELECT `several` FROM `survey` WHERE `email` = '{$_GET['email']}'")->fetch();
        echo $result["several"];
        break;

    case "getIsSurvey":
        $result = $conn->query("SELECT * FROM `survey` WHERE `email` = '{$_GET['email']}'")->fetchAll();
        if (empty($result)) {
            echo 0;
        } else {
            echo 1;
        }
        break;
}