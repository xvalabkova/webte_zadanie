<?php
require_once "classes/MyPDO.php";

// <!-- ------------------------------------------------------------------------------------------------------------------------ -->
//email, on which are going to be sent logs in .cvs file.
// $email='frantisekbazos@gmail.com';
$email = 'emma.valabkova@gmail.com';

// <!-- ------------------------------------------------------------------------------------------------------------------------ -->
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);

// <!-- ------------------------------------------------------------------------------------------------------------------------ -->
$servername = "localhost";
// $dbname = "zaver";
// $username = "xhancin";
// $password = "xNT6Mf0aXZagQFw"; 

$dbname = "Zaver";
$username = "xvalabkova";
$password = "hesloheslo";

// $dbname = "zaver";
// $username = "xpolednakp";
// $password = "ZAoSKBBUksTuJ8Q";

// <!-- ------------------------------------------------------------------------------------------------------------------------ -->
$simulation_coeficient = 70;

// <!-- ------------------------------------------------------------------------------------------------------------------------ -->
$myPdo = new MyPDO("mysql:host=$servername;dbname=$dbname", $username, $password);

// <!-- ------------------------------------------------------------------------------------------------------------------------ -->
$apiKeyForOctaveAPI="you_have_to_write_this";
?>