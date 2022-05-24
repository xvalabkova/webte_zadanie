<?php
header('Content-Type: application/json; charset=utf-8');
require_once "../config.php";
require_once "../classes/Command.php";

$data = $myPdo->run("SELECT * FROM logs")->fetchAll();
echo json_encode($data);
?>