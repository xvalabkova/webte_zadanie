<?php
require_once "classes/MyPDO.php";

// <!-- ------------------------------------------------------------------------------------------------------------------------ -->

$servername = "localhost";
$dbname = "Zaver";
$username = "xvalabkova";
$password = "hesloheslo";

// $dbname = "zaver";
// $username = "xpolednakp";
// $password = "ZAoSKBBUksTuJ8Q";
// <!-- ------------------------------------------------------------------------------------------------------------------------ -->

$myPdo = new MyPDO("mysql:host=$servername;dbname=$dbname", $username, $password);
?>