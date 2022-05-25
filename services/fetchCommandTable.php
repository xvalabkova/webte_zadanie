<?php
header('Content-Type: application/json; charset=utf-8');
require_once "../config.php";
require_once "../classes/Command.php";

$data = $myPdo->run("SELECT * FROM logs")->fetchAll();

//Source of code: https://www.w3docs.com/snippets/php/automatic-download-file.html

$filename = '../files/logs.csv';
$f = fopen($filename, 'w');
if ($f === false) {
	die('Error opening the file ' . $filename);
}

fputcsv($f, ['id','command','exit_code','error_message','timestamp'],';');
foreach ($data as $row) {
	fputcsv($f, $row,';');
}
fclose($f);

if(file_exists($filename)) {
    header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($filename).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filename));
    flush(); // Flush system output buffer
    readfile($filename);
    //header("location: ../index.php");
    die();
} else {
    http_response_code(404);
    die();
}
echo json_encode($data);
?>