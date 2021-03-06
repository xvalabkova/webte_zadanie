
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="xvalabkova, xpolednakp, xhancin">
    <meta name="description" content="Computer Aided System - Oracle">

    <title>Webte2 Záverečné zadanie</title>
    <link rel="apple-touch-icon" sizes="180x180" href="icons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="icons/favicon-16x16.png">
    <link rel="manifest" href="icons/site.webmanifest">

    <!-- Bootstrap styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- Font Awesome icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">

    <!-- Custom styles -->
    <link rel="stylesheet" href="styles/myStyles.css">

</head>

<!-- ---------------------------------------------------------------------------------------------------------------- -->

<?php
require_once "config.php";
require_once "classes/Command.php";

session_start();
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'sk';
}


if (isset($_POST['octave_command_btn'])) {
    $command = strip_tags($_POST['oc_command']);
    $command = str_replace(array("\n", "\r"), '', $command);
    $commands = explode(";", $command);
    $commandLineOutput = "";

    foreach ($commands as $command) {
        $myfile = fopen("form_commands.m", "w") or die("Unable to open file!");
        $txt = "";

        if ($command != "") {
            $txt = $txt . "disp(\"$command=\"),disp(" . $command . ");\n";
            fwrite($myfile, $txt);
            fclose($myfile);

            $cmd = "octave -qf form_commands.m";
        }}}
    if(isset($_POST['sendKey'])){
        $arr_cookie_options = array (
            'expires' => time() + 60*60*24*10,
            'path' => '/',
           // 'domain' => '.example.com', // leading dot for compatibility or use subdomain
            'secure' => true,     // or false
            //'httponly' => true,    // or false
            'samesite' => 'None' // None || Lax  || Strict
            );
        if($apiKeyForOctaveAPI==$_POST['key_value']){
            setcookie('ValidUser', 'true', $arr_cookie_options);  // 10 days cookies
            $_COOKIE['ValidUser']="true";                                               // set manually, so it works without refreshing the page
        }       // else??   
    }

// <!-- ---------------------------------------------------------------------------------------------------------------- -->

    if(isset($_POST['octave_command_btn']) && isset($_COOKIE['ValidUser']) && $_COOKIE["ValidUser"]=="true"){        // if button for sending commands from command line was clicked
        $command = strip_tags($_POST['oc_command']);
        $command = str_replace(array("\n", "\r"), '', $command);
        $commands = explode(";",$command);          // seperate multiple commands
        $commandLineOutput = "";

        $cmd = "octave -qf form_commands.m";
        $overall_retval = 0;
        $variables = [];

        foreach($commands as $command) {
            while ($command[0] == "\n" || $command[0] == " ") {
                $command = substr($command, 1);
            }
            $myfile = fopen("form_commands.m", "w") or die("Unable to open file!");
            $txt = "";
            
            if ($command != "") {
                if (!strpos($command, '=') || strpos($command, '=') == strlen($command)-1) {
                    $txt = $txt."printf(\"$command = %d\\n\", ".$command .");\n";
                } else {
                    $txt = $txt.$command.";\n";
                    array_push($variables, $command[0]);
                }      // prepare command to be executed by octave // expected output from octave: "1+1=2 \n"
                fwrite($myfile, $txt);                                          // send command to file, that will be executed
                fclose($myfile);
                
                $full_txt = $full_txt.$txt;

                $output=null;
                $retval=null;
                exec($cmd.' 2>&1', $output, $retval);
                
                if ($retval != 0) {
                    if (strpos($command, '=') && !in_array($command[0], $variables)) {
                        $overall_retval = implode("\n", $output);
                    } else $retval = 0;
                }    

                // <!-- -------------------------------------------------------------------- -->
                // log to databaze, $myPdo is from file config.php 

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
        } 
        
        if ($overall_retval == 0) {
            $myfile = fopen("form_commands.m", "w") or die("Unable to open file!");
            fwrite($myfile, $full_txt);
            fclose($myfile);
            $output = null;
            exec($cmd.' 2>&1', $output, $retval);
            $commandLineOutput = implode("\n", $output);
        } else {
            $commandLineOutput = $overall_retval;
        }     // may contain return values for multiple commands
    }
    
?>
 <!-- ---------------------------------------------------------------------------------------------------------------- -->


<?php 
function langSwitch($skTranslation, $enTranslation) {       // function decides which value to print out based on the currently set language 
    if ($_SESSION['lang'] == 'sk') 
        echo $skTranslation; 
    else if ($_SESSION['lang'] == 'en') 
        echo $enTranslation;
}
?>

<body>
    <section id="title">
            
                
        <div class="container-fluid">

        <nav class="navbar navbar-expand-lg navbar-dark">
            <a class="navbar-brand" href=""><?php langSwitch('Octave... dočasné meno', 'Octave... placeholder name');?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php langSwitch('Ciele zadania', 'Targets of the assignment');?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href=""><?php langSwitch('Dvojjazyčnosť', 'Bilingualist'); echo '   <i class="fas lvl fa-check-circle"></i>'?></a></li>
                            <li><a class="dropdown-item" href=""><?php langSwitch('API ku CAS s API kľúčom', 'API to CAS with an API key'); echo '   <i class="fas lvl fa-check-circle"></i>'?></a></li>
                            <li><a class="dropdown-item" href=""><?php langSwitch('Animácia', 'Animation'); echo '   <i class="fas lvl fa-check-circle"></i>'?></a></li>
                            <li><a class="dropdown-item" href=""><?php langSwitch('Graf', 'Plot'); echo '   <i class="fas lvl fa-check-circle"></i>'?></a></li>
                            <li><a class="dropdown-item" href=""><?php langSwitch('API formulár', 'API form'); echo '   <i class="fas lvl fa-check-circle"></i>'?></a></li>
                            <li><a class="dropdown-item" href=""><?php langSwitch('Logovanie, export to CSV', 'Logging, export to CSV'); echo '   <i class="fas lvl fa-check-circle"></i>'?></a></li>
                            <li><a class="dropdown-item" href=""><?php langSwitch('Export popisu do PDF', 'Export to PDF'); echo '   <i class="fas lvl fa-check-circle"></i>'?></a></li>
                            <li><a class="dropdown-item" href=""><?php langSwitch('Synchrónne sledovanie', 'Synchronous viewing'); echo '   <i class="fas lvl fa-times-circle"></i>'?></a></li>
                            <li><a class="dropdown-item" href=""><?php langSwitch('Docker balíček', 'Docker'); echo '   <i class="fas lvl fa-times-circle"></i>'?></a></li>
                            <li><a class="dropdown-item" href=""><?php langSwitch('Používanie verziovacieho systému', 'Usage of version system'); echo '   <i class="fas lvl fa-check-circle"></i>'?></a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php langSwitch('Výber jazyka', 'Language selection');?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="services/switchLanguage.php">Slovenčina <?php if ($_SESSION['lang'] == 'sk') echo '<i class="fas lvl fa-check-circle"></i>'?></a></li>
                            <li><a class="dropdown-item" href="services/switchLanguage.php">English <?php if ($_SESSION['lang'] == 'en') echo '<i class="fas lvl fa-check-circle"></i>'?></a></li>
                        </ul>
                    </li>
                  
                   
                    <li class="nav-item">
                            <a id="placeholder2" class="nav-link"><?php langSwitch('Popis stránky', 'Site description'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a id="placeholder3" class="nav-link" href="services/fetchCommandTable.php?mode=down"><?php langSwitch('Stiahnite logy', 'Download logs'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a id="placeholder4" class="nav-link" href="services/fetchCommandTable.php?mode=mail"><?php langSwitch('Pošli logy na mail', 'Send logs to mail'); ?></a>
                        </li>
                </ul>
            </div>
        </section> 


     <!-- ---------------------------------------------------------------------------------------------------------------- -->


    <section class="content">

        <div class="container">

            <div class="row justify-content-center">
                <h3 class="text-center" style="padding: 1rem auto 0;"><?php langSwitch('Úvodná stránka', 'Welcome page');?></h3>


                <!-- Api key form container-->
                <div class= container>
                
                <section>
                    <!-- don't show this div if user is already valid -->
                        <div class="container  <?php if(isset($_COOKIE['ValidUser']) && $_COOKIE['ValidUser']=='true') echo ' hidden'; else echo '';   ?>" >
                            <h5 style="margin: 0 auto 1rem;"><?php langSwitch('Zadaj API kľúč', 'Write API key:');?></h5>
                        <form action="index.php" method="post" >
                                <div class="input-group">
                                        <input type="text" class="form-control" id="api_k" name="key_value">
                                        <div class="input-group-append">
                                            <button type="submit" id="sendKey" name="sendKey" class="btn btn-dark"><?php langSwitch('Skontroluj:', 'Chceck:');?></button>
                                    </div>
                                </div>
                               
                            </form>
                        </div>
                </section>
                </div>

                

                <!-- container for parameters -->
                <div class="container">
                    <br>
                    <div class="row">
                        <h5 style="margin: 0 auto 1rem;"><?php langSwitch('Zvoľ paramatre', 'Fill in parameters:');?></h5>

                        <!-- Form for weight of objects and height of obstacle -->
                        <form action="index.php" method="post" id="param-form" class="form-group">
                            
                            <div class="one-to-one-grid">
                                <div class="form-group row">
                                    <label for="weight1" class="col-sm-2 col-form-label"><?php langSwitch('Hmotnosť 1:', 'Weight 1:'); ?></label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="weight1" name="m1" placeholder="kg">
                                    </div>
                                </div>

                                <br>

                                <div class="form-group row">
                                    <label for="weight2" class="col-sm-2 col-form-label"><?php langSwitch('Hmotnosť 2:', 'Weight 2:'); ?></label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="weight2" name="m2" placeholder="kg">
                                    </div>
                                </div>

                                <div class="form-check">
                                    <label class="form-check-label"><?php langSwitch('Animácia:', 'Animation:'); ?><input class="form-check-input" type="checkbox" name="animation" id="animation"></label>
                                </div>

                                <div class="form-group row">
                                    <label for="height" class="col-sm-2 col-form-label"><?php langSwitch('Výška prekážky:', 'Obstacle height:'); ?></label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="height" name="r" placeholder="cm">
                                        <small id="r_warning" class="">*must be smaller than 60cm</small>
                                    </div>
                                </div>

                                <div class="form-check">
                                    <label class="form-check-label"><?php langSwitch('Graf:', 'Plot:'); ?><input class="form-check-input" type="checkbox" name="plot" id="plot"></label>
                                </div>
                            </div>

                            <p class="hidden" id="warning-message" style="font-size: 1.2rem; color:red"></p>
                            <button type="button" id="sendBtn" name="sendBtn" class="btn btn-dark"><?php langSwitch('Spusti:', 'Start:');?></button><br><br>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    <!-- ---------------------------------------------------------------------------------------------------------------- -->
    <!-- Divs preparvases -->

        <div class="canvases" id="animation-canvas2" style="margin-bottom: 1rem;"> 
             <section id="sec">
                <!-- animation -->
                <div id="animation-canvas" class="container hidden">
                </div>
            </section>

            <section>
                <!-- plot -->
                <div id="plot-canvas" class="container hidden">
                </div>
            </section>
        </div>

    <!-- ---------------------------------------------------------------------------------------------------------------- -->
    <!-- Command line input -->

        <section>
            <!-- command line  -->
            <div class="container border">
                <br>
                <h5><?php langSwitch('Príkazový riadok pre Octave:', 'Test command line for Octave:'); ?></h5>

                <!-- Form sends command to a function to be executed, then prints output -->
                <form action="index.php" method="post" class="form-group">
                    <textarea id="output" name="out_oc" class="form-control" style="height:110px;" readonly><?php if (isset($_POST['octave_command_btn'])) {
                                                                                                                echo $commandLineOutput;
                                                                                                            } ?></textarea>
                    <div class="input-group">

                        <textarea id="command" name="oc_command" class="form-control" style="height:18px;"></textarea>

                        <div class="input-group-append">

                            <button class="input-group-text btn btn-dark" name="octave_command_btn"><?php langSwitch('Pošli:', 'Send:'); ?></button>

                        </div>
                    </div>
                </form>
                <br>
            </div>
        </section>

<!-- ---------------------------------------------------------------------------------------------------------------- -->
<!-- API description -->
        <br><br>

    </section>
    <div class="modal" id="modal">
        <div class="modal-content">
            <div class="close">&#10006;</div>
            <div class="modaldata">
                <div id="htmldata">
                    <h3><?php langSwitch('Podis stránky', 'Site description'); ?></h3>
                    <p><?php langSwitch(
                            'Pred používaním funkcií stránky je nutné sa autorizovať cez API kľúč.
                            Stránka poskytuje API pre program Octave formou príkazového riadku,
            kde po odoslaní príkazu príde odpoveď aj s výsledkom operácie. Ďalšou funkciou je 
            animácia dynamického systému "tlmič automobil", vrátane grafu zobrazujúceho priebeh, kde používateľ zadá parametre "Hmotnosť 1","Hmotnosť 2" a 
            "výška", v checkboxoch si zvolí či chce zobraziť graf alebo aj animáciu tlmiča a spustí. Ak necháte parametre prázdne a 
            spustíte dosadia sa východiskové parametre. V menu, po kliknutí "Výber jazyka" je možné dynamicky prepínať medzi SK/EN.
            Po kliknutí "Stiahnite logy" sa stiahne súbor logs.csv obsahujúci logy z databázy. Po kliknutí "Pošli logy na mail" 
            sa odošle mail na adresu zo súboru config.php so súborom logs.csv v prílohe mailu.',

                            'Before using site functions is necessary to authenticate with API key, listed in file config.php.
                            This site contains API with Octave comand line, where you put your comand and API server sends you result with
            output. The next function, this site provides, is animation of dynamic system "Car shock absorber"
            , which contains drawing a graph of process and animation of shock absorber. Visitor of the site fills
            the paramethers "Weight 1", "Weight 2" and "Obstacle height", then selects the mode in checkboxes(drawing graph, animation)
            and starts. If you leave parameters empty, there will be setted default parameters. In menu "Language selection" can user dynamically change language between SK/EN.
            In menu "Download logs" you can download database data in logs.csv file. In menu "Send logs to mail" you can send mail to address written in config.php file
            with file logs.csv in attachment.'
                        ); ?></p>
                </div>
                <form action="pdf.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="htmlData" id="htmlData" value="...">
                    <input class="btn btn-dark" type="submit" value="<?php langSwitch('Generuj do pdf', 'Generate to pdf'); ?>" name="pdf">
                </form>
            </div>
        </div>
    </div>
    <div class="modal" id="modal2">
        <div class="modal-content">
            <div class="close">&#10006;</div>
            <div class="modaldata">
                <h3 id="sentMail" class="text-success"><?php langSwitch('Logy boli zaslané na mail: '.$email, 'Logs have been sent to mail: '.$email); ?></h3>
            </div>
        </div>
    </div>

    <?php
    if (isset($_GET['sent']) && $_GET['sent'] == "1") {
        echo '<script type="text/javascript">
    document.getElementById("modal2").style.display = "block";
    </script>';
    }
    ?>

    <!-- ---------------------------------------------------------------------------------------------------------------- -->

    <script>
         function getCookie(cname) {
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for(let i = 0; i <ca.length; i++) {
      let c = ca[i];
      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
    }
    return "";
  }
    </script>

    <!-- Bootstrap script-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

    <!-- Plotly -->
    <script src="https://cdn.plot.ly/plotly-2.12.1.min.js"></script>

    <script src="scripts/plot.js" defer></script>
    <script src="scripts/main.js"></script>

    <link href="styles/modal.css" rel="stylesheet">


    <!-- p5.js library for animation -->
    <script src="https://cdn.jsdelivr.net/npm/p5@1.4.1/lib/p5.js"></script>

    <!-- My script, for animation -->
    <script src="scripts/sketch.js"></script>
</body>

</html>