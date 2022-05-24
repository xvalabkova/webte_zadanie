<?php 

session_start();
if (!isset($_SESSION['lang']) || $_SESSION['lang'] == 'sk') 
    $_SESSION['lang'] = 'en';
else $_SESSION['lang'] = 'sk';
header('Location: ../index.php');
?>