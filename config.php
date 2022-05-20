<?php


// <!-- ------------------------------------------------------------------------------------------------------------------------ -->

$dbconfig = array(
    'hostname' => 'localhost',
    'username' => 'xvalabkova', //MySQL username
    'password' => 'hesloheslo',
    'database' => 'Zaver',
);

// <!-- ------------------------------------------------------------------------------------------------------------------------ -->

$db = new mysqli();
$db->connect($dbconfig['hostname'],$dbconfig['username'],$dbconfig['password']);
$db->select_db($dbconfig['database']);
if(!$db){
    die("Connection failed...");
    //echo "<h1>Database connection error.</h1>";
}