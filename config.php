<?php
require_once "classes/MyPDO.php";

// <!-- ------------------------------------------------------------------------------------------------------------------------ -->

$servername = "localhost";
$dbname = "zaver";
$username = "xpolednakp";
$password = "ZAoSKBBUksTuJ8Q";

$simulation_coeficient = 60;

// <!-- ------------------------------------------------------------------------------------------------------------------------ -->

$myPdo = new MyPDO("mysql:host=$servername;dbname=$dbname", $username, $password);
?>