<?php

include_once "./db.php";

switch ($_POST["mode"]) {
    case "post":
        for ($i=1; $i < 199 ; $i++) {
            $name = $i;
            $email = "{$i}@{$i}.{$i}";
            $conn->exec("INSERT INTO `survey`(`name`,`email`) VALUES ('{$name}','{$email}')");
        }
        break;

    case "addNewBus":
        if ($_POST['time'] >= 0) {
            $sql = "INSERT INTO `bus`(`name`, `time`) VALUES ('{$_POST['name']}','{$_POST['time']}')";
            $conn->exec($sql);
        }
        break;

    case "addNewStation":
        if ($_POST['time'] >= 0 && $_POST['wait'] >= 0) {
            $sql = "INSERT INTO `station`(`name`, `time`, `wait`) VALUES ('{$_POST['name']}','{$_POST['time']}','{$_POST['wait']}')";
            $conn->exec($sql);
        }
        break;

    case "editBusData":
        if ($_POST['time'] >= 0) {
            $sql = "UPDATE `bus` SET `time`='{$_POST['time']}' WHERE `id` = '{$_POST['id']}'";
            $conn->exec($sql);
        }
        break;

    case "editStationData":
        if ($_POST['time'] >= 0 && $_POST['wait'] >= 0) {
            $sql = "UPDATE `station` SET `time`= '{$_POST['time']}', `wait` = '{$_POST['wait']}' WHERE `id` = '{$_POST['id']}'";
            $conn->exec($sql);
        }
        break;

    case "deleteBus":
        $sql = "DELETE FROM `bus` WHERE `id` = '{$_POST['id']}'";
        $conn->exec($sql);
        break;

    case "deleteStation":
        $sql = "DELETE FROM `station` WHERE `id` = '{$_POST['id']}'";
        $conn->exec($sql);
        break;

    case "renewRow":
        if ($_POST['rowNum'] > 0) {
            $sql = "UPDATE `admin` SET `rowNum` = '{$_POST['rowNum']}'";
            $conn->exec($sql);
        }
        break;

    case "renewTime":
        if ($_POST['renewTime'] > 0) {
            $sql = "UPDATE `admin` SET `renewTime` = '{$_POST['renewTime']}'";
            $conn->exec($sql);
        }
        break;

    case "sortStationTable":
        if ($_POST["truncateTable"] == "true") {
            $sql = "TRUNCATE TABLE `station`";
            $conn->exec($sql);
        }
        $sql = "INSERT INTO `station`(`name`, `time`, `wait`) VALUES ('{$_POST["name"]}','{$_POST["time"]}','{$_POST["wait"]}')";
        $conn->exec($sql);
        break;

    // project 3

    case "addNewParticipant":
        $sql = "INSERT INTO `participant`(`email`) VALUES ('{$_POST["email"]}')";
        $conn->exec($sql);
        break;

    case "editParticipantData":
        $sql = "UPDATE `participant` SET `email` = '{$_POST['email']}' WHERE `id` = '{$_POST['id']}'";
        $conn->exec($sql);
        break;

    case "deleteParticipant":
        $sql = "DELETE FROM `participant` WHERE `id` = '{$_POST['id']}'";
        $conn->exec($sql);
        break;

    case "addNewSurvey":
        $sql = "INSERT INTO `survey`(`name`, `email`) VALUES ('{$_POST["name"]}','{$_POST["email"]}')";
        $conn->exec($sql);
        break;

    case "deleteSurvey":
        $sql = "DELETE FROM `survey` WHERE `id` = '{$_POST['id']}'";
        $conn->exec($sql);
        break;

    case "editSurveyData":
        $sql = "UPDATE `survey` SET `name` = '{$_POST['name']}' WHERE `id` = '{$_POST['id']}'";
        $conn->exec($sql);
        break;

    case "produceBus":
        $count = $conn->query("SELECT COUNT(*) / 50 FROM `survey` WHERE `several` = ''")->fetchColumn();
        $randomArray = [];
        while (count($randomArray) < $count) {
            $randomName = "AUTO-" . sprintf("%04d", rand(1, 9999));
            if (!in_array($randomName, $randomArray)) {
                array_push($randomArray, $randomName);
            }
        }
        $records = $conn->query("SELECT `id` FROM `survey` WHERE `several` = ''")->fetchAll();
        foreach ($records as $idx => $record) {
            $randomIndex = intdiv($idx, 50);
            $randomValue = $randomArray[$randomIndex];
            $sql = "UPDATE `survey` SET `several` = '{$randomValue}' WHERE `id` = {$record['id']}";
            $conn->exec($sql);
        }
        break;
}