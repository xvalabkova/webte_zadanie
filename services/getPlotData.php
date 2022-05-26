<?php
header('Content-Type: application/json; charset=utf-8');
require_once "../config.php";
require_once "../classes/Command.php";

$data;
$paramDeclaration = "";
$overall_retval = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['m1']) && is_numeric($data['m1'])) {
        if ($data['m1'] > 1000) {
            $paramDeclaration = $paramDeclaration."m1 = ".$data['m1']."; ";
        } else $overall_retval = -1;
    }   else $paramDeclaration = $paramDeclaration."m1 = 2500; ";

    if (isset($data['m2']) && is_numeric($data['m2'])) {
        if ($data['m2'] > 100) {
            $paramDeclaration = $paramDeclaration."m2 = ".$data['m2']."; ";
        } else $overall_retval = -1;
    }   else $paramDeclaration = $paramDeclaration."m2 = 320; ";

    if (isset($data['r']) && is_numeric($data['r'])) {
        if($data['r'] < 61 && $data['r'] > -61) {
            $paramDeclaration = $paramDeclaration."r = ".$data['r']."; ";
        }   else $overall_retval = -1;
    } else $paramDeclaration = $paramDeclaration."r = 0.1; ";
} else $paramDeclaration = "m1 = 2500; m2 = 320; r =0.1;";

$originalCode = "pkg load control; ".$paramDeclaration."

k1 = 80000; k2 = 500000;
b1 = 350; b2 = 15020;
A=[0 1 0 0;-(b1*b2)/(m1*m2) 0 ((b1/m1)*((b1/m1)+(b1/m2)+(b2/m2)))-(k1/m1) -(b1/m1);b2/m2 0 -((b1/m1)+(b1/m2)+(b2/m2)) 1;k2/m2 0 -((k1/m1)+(k1/m2)+(k2/m2)) 0];
B=[0 0;1/m1 (b1*b2)/(m1*m2);0 -(b2/m2);(1/m1)+(1/m2) -(k2/m2)];
C=[0 0 1 0]; D=[0 0];
Aa = [[A,[0 0 0 0]'];[C, 0]];
Ba = [B;[0 0]];
Ca = [C,0]; Da = D;
K = [0 2.3e6 5e8 0 8e6];
sys = ss(Aa-Ba(:,1)*K,Ba,Ca,Da);

t = 0:0.01:5;
initX1=0;
initX1d=0;
initX2=0;
initX2d=0;
[y,t,x]=lsim(sys*[0;1],r*ones(size(t)),t,[initX1;initX1d;initX2;initX2d;0]);
";

$codeAddition = "r = -10;
[y,t,x]=lsim(sys*[0;1],r*ones(size(t)),t,x(size(x,1),:));";

$originalCode = strip_tags($originalCode);
$originalCode = str_replace(array("\n", "\r"), '', $originalCode);
$commands = explode(";",$originalCode);

$lang;
session_start();
    if ($_SESSION['lang'] != 'en') {
        $lang = 'sk';
    } else $lang = 'en';

foreach($commands as $command) {
    $myfile = fopen("../graph_code.m", "w") or die("Unable to open file!");
    fwrite($myfile, $command);
    fclose($myfile);
    exec($cmd.' 2>&1', $output, $retval);
    if ($retval != 0) {
        $overall_retval = implode("\n", $output);
    } else if ($command != ""){
        try {
            $test = new Command($myPdo);
            $test->setCommand($command);
            $test->setExitCode($retval);
            $retval == 0 ? $test->setErrorMessage() : $test->setErrorMessage(implode($output));
            $test->setTimestamp(date("H:i:s  d.m.Y"));
            $test->save(); 
        } catch(PDOException $e) {
            echo "Error: ". $e->getMessage();
        }
    }
};

if ($overall_retval == 0) {
    $myfile = fopen("../graph_code.m", "w") or die("Unable to open file!");
    fwrite($myfile, $originalCode."t");
    fclose($myfile); 
    $cmd = "octave -qf ../graph_code.m";

    $t=null;
    $y=null;
    $x1=null;
    $retval=null;
    exec($cmd, $t, $retval);

    $myfile = fopen("../graph_code.m", "w") or die("Unable to open file!");
    fwrite($myfile, $originalCode."y");
    fclose($myfile);
    exec($cmd, $y, $retval);

    $myfile = fopen("../graph_code.m", "w") or die("Unable to open file!");
    fwrite($myfile, $originalCode."x(:,1)");
    fclose($myfile);
    exec($cmd, $x1, $retval);

    $result = ["y" => $y, "t" => $t, "x1" => $x1, "c" => $simulation_coeficient, "lang" => $lang];
    echo json_encode($result);
} else {
    $result = ["y" => "Error transpired", "lang" => $lang];
    echo json_encode($result);
}
?>