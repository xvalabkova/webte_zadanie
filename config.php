<?php
require_once "classes/MyPDO.php";
//email, on which are going to be sended logs in .cvs file.
$email='frantisekbazos@gmail.com';
// <!-- ------------------------------------------------------------------------------------------------------------------------ -->

$servername = "localhost";
$dbname = "zaver";
$username = "xhancin";
$password = "xNT6Mf0aXZagQFw";

// <!-- ------------------------------------------------------------------------------------------------------------------------ -->

$myPdo = new MyPDO("mysql:host=$servername;dbname=$dbname", $username, $password);
ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
?>