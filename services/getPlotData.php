<?php
header('Content-Type: application/json; charset=utf-8');
$data;
$paramDeclaration = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    // TODO: Sanity checks
    if (isset($data['m1']) && is_numeric($data['m1'])) 
        $paramDeclaration = $paramDeclaration."m1 = ".$data['m1']."; ";
    else $paramDeclaration = $paramDeclaration."m1 = 2500; ";

    if (isset($data['m2']) && is_numeric($data['m2'])) 
        $paramDeclaration = $paramDeclaration."m2 = ".$data['m2']."; ";
    else $paramDeclaration = $paramDeclaration."m2 = 320; ";

    if (isset($data['r']) && is_numeric($data['r'])) 
        $paramDeclaration = $paramDeclaration."r = ".$data['r']."; ";
    else $paramDeclaration = $paramDeclaration."r = 50; ";
} else $paramDeclaration = "m1 = 2500; m2 = 320; r =50;";

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

$originalCode = strip_tags($originalCode);
$originalCode = str_replace(array("\n", "\r"), '', $originalCode);

$myfile = fopen("../graph_code.m", "w") or die("Unable to open file!");
fwrite($myfile, $originalCode."t");
fclose($myfile);

$cmd = "octave -qf ../graph_code.m";

$t=null;
$y=null;
$retval=null;
exec($cmd, $t, $retval);

$myfile = fopen("../graph_code.m", "w") or die("Unable to open file!");
fwrite($myfile, $originalCode."y");
fclose($myfile);
exec($cmd, $y, $retval);

$result = ["y" => $y, "t" => $t];
echo json_encode($result);
?>