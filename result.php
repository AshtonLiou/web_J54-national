<?php

include_once "./api/db.php";

$bus = $conn->query("SELECT DISTINCT `several` FROM `survey` WHERE `several` != ''")->fetchAll();

header("Content-Type: application/json");

if ($bus) {
    foreach ($bus as $busData) {
        $several = $busData["several"];
        $participants = $conn->query("SELECT `id`, `name`, `email` FROM `survey` WHERE `several` = '{$several}'")->fetchAll(PDO::FETCH_ASSOC);
        $result[] = ["bus" => $several, "participants" => $participants];
    }

    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}