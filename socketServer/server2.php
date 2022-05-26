<?php
//php server2.php start
use Workerman\Worker;
use Workerman\Lib\Timer;

require_once __DIR__ . '/vendor/autoload.php';
//require_once '/home/zakova/workerman-4.0.31/Autoloader.php';

function Message($name,$users)
{
    //$time = date('h:i:s');
    //$num=rand(0, intval($maxRandNum));  
    $obj = new stdClass();
    $obj->name = $name;
    //$obj->userNames = $unames;
    $obj->users = $users;
    return json_encode($obj);
}

// SSL context.
$context = [
    'ssl' => [
        'local_cert'  => '/home/xhancin/webte.fei.stuba.sk-chain-cert.pem',
        'local_pk'    => '/home/xhancin/webte.fei.stuba.sk.key',
        'verify_peer' => false,
    ]
];

// Create A Worker and Listens 9000 port, use Websocket protocol
$ws_worker = new Worker("websocket://0.0.0.0:9000", $context);

// Enable SSL. WebSocket+SSL means that Secure WebSocket (wss://). 
// The similar approaches for Https etc.
$ws_worker->transport = 'ssl';

// 4 processes
$ws_worker->count = 1;
$GLOBALS['users']=null;
$GLOBALS["userNames"]=null;

// Add a Timer to Every worker process when the worker process start
$ws_worker->onWorkerStart = function ($ws_worker) {
    // Emitted when new connection come
    $ws_worker->onConnect = function ($connection) {
        // Emitted when websocket handshake done
        $connection->onWebSocketConnect = function ($connection) {
            $GLOBALS['users'][$connection->getRemotePort()]=null; 
            echo "New connection " . $connection->getRemotePort() . "\n";
            
        };
    };

    $ws_worker->onMessage = function ($connection, $data) {
        if (isJSON($data)) {
            $obj = json_decode($data, true);
            if ($obj['first'] && $obj['name'] ){
                $GLOBALS["users"][$connection->getRemotePort()]=['name'=>$obj['name'],'cmdLine'=>[],'plotData'=>[]];
                
            }
            if($obj['cmdLine']){
                //var_dump($obj['cmdLine']);
                //$GLOBALS["users"][$connection->getRemotePort()]['cmdLine']=['cmdLine'=>$obj['cmdLine']];
                //var_dump($obj['cmdLine']);
                $GLOBALS["users"][$connection->getRemotePort()]['cmdLine']=$obj['cmdLine'];
                //var_dump( $GLOBALS["users"]);
                
            }
            if($obj['plotData']){
                $GLOBALS["users"][$connection->getRemotePort()]['plotData']=$obj['plotData'];
            }
            var_dump($GLOBALS["users"]);   
               
        }
    
        //$connection->send(Message("sprava1",null));
        $connection->send(Message(null,$GLOBALS["users"]));

    };

    // Emitted when connection closed
    $ws_worker->onClose = function ($connection) {
       
        unset($GLOBALS["users"][$connection->getRemotePort()]);
        var_dump($GLOBALS["users"]);
        echo "Connection closed" . "\n";
    };
    //bije sa to casovo asi, posli ren raz send 
    // Timer every 5 seconds
    Timer::add(
        3,  //2.5, 1, 0.01,0.08
        function () use ($ws_worker) {
            
            foreach ($ws_worker->connections as $connection) {
                //$connection->send(Message("sprava2"));
                $connection->send(Message(null,$GLOBALS["users"]));
            }
             }
);
};
// Run worker
Worker::runAll();

function commmon($dve)
{
    return current(array_intersect($dve[0], $dve[1]));  //dava cislo ,nie index!
}
function isJSON($string)
{
    return is_string($string) && is_array(json_decode($string, true)) ? true : false;
}
    /*
    php server2.php start
php start.php start -d
php start.php status
php start.php connections
php start.php stop
php start.php restart
php start.php reload

cd /var/www/site70.webte.fei.stuba.sk/webte-zadanie/socketServer
 */
