<?php

include_once "./api/db.php";

$result = $conn->query("SELECT * FROM `participant`")->fetchAll(PDO::FETCH_ASSOC);

header("Content-Type: application/json");
echo json_encode($result, JSON_UNESCAPED_UNICODE);