<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="xvalabkova, xpolednakp, xhancin">
    <meta name="description" content="Computer Aided System - Octave">
    
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

// <!-- ---------------------------------------------------------------------------------------------------------------- -->

    if(isset($_POST['octave_command_btn'])){        // if button for sending commands from command line was clicked
        $command = strip_tags($_POST['oc_command']);
        $command = str_replace(array("\n", "\r"), '', $command);
        $commands = explode(";",$command);          // seperate multiple commands
        $commandLineOutput = "";

        foreach($commands as $command) {
            $myfile = fopen("form_commands.m", "w") or die("Unable to open file!");
            $txt = "";
            
            if ($command != "") {
                $txt = $txt."disp(\"$command=\"),disp(".$command .");\n";       // prepare command to be executed by octave // expected output from octave: "1+1=2 \n"
                fwrite($myfile, $txt);                                          // send command to file, that will be executed
                fclose($myfile);
                
                $cmd = "octave -qf form_commands.m";

                $output=null;
                $retval=null;
                exec($cmd.' 2>&1', $output, $retval);
                
                $commandLineOutput = $commandLineOutput.(implode($output))."\n";    // may contain return values for multiple commands


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

 <!-- ---------------------------------------------------------------------------------------------------------------- -->
<!-- Navbar -->

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
                            <?php langSwitch('Výber jazyka', 'Language selection');?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="services/switchLanguage.php">Slovenčina <?php if ($_SESSION['lang'] == 'sk') echo '<i class="fas lvl fa-check-circle"></i>'?></a></li>
                            <li><a class="dropdown-item" href="services/switchLanguage.php">English <?php if ($_SESSION['lang'] == 'en') echo '<i class="fas lvl fa-check-circle"></i>'?></a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a id="placeholder2" class="nav-link" href="index.php"><?php langSwitch('Predpoveď počasia', 'Weather forecast');?></a>
                    </li>
                    <li class="nav-item">
                        <a id="placeholder3" class="nav-link" href="index.php"><?php langSwitch('Štatistika', 'Statistics');?></a>
                    </li>
                </ul>
            </div>
        </nav>
        </div>
    </section>


     <!-- ---------------------------------------------------------------------------------------------------------------- -->


    <section class="content">
        <div class="container">
        
            <div class="row justify-content-center">
                <h3 class="text-center" style="padding: 1rem auto 0;"><?php langSwitch('Úvodná stránka', 'Welcome page');?></h3>
                
                <!-- container for parameters -->
                <div class="container">
                    <br>
                    <div class="row">
                        <h5 style="margin: 0 auto 1rem;"><?php langSwitch('Zvoľ paramatre', 'Fill in parameters:');?></h5>

                        <!-- Form for weight of objects and height of obstacle -->
                        <form action="index.php" method="post" id="param-form" class="form-group">
                            
                            <div class="one-to-one-grid">
                                <div class="form-group row">
                                    <label for="weight1" class="col-sm-2 col-form-label"><?php langSwitch('Hmotnosť 1:', 'Weight 1:');?></label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="weight1" name="m1" placeholder="kg">
                                    </div>
                                </div>

                                <br>

                                <div class="form-group row">
                                    <label for="weight2" class="col-sm-2 col-form-label"><?php langSwitch('Hmotnosť 2:', 'Weight 2:');?></label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="weight2" name="m2" placeholder="kg">
                                    </div>
                                </div>

                                <div class="form-check">
                                    <label class="form-check-label"><?php langSwitch('Animácia:', 'Animation:');?><input class="form-check-input" type="checkbox" name="animation" id="animation"></label>
                                </div>

                                <div class="form-group row">
                                    <label for="height" class="col-sm-2 col-form-label"><?php langSwitch('Výška prekážky:', 'Obstacle height:');?></label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="height" name="r" placeholder="cm">
                                    </div>
                                </div>

                                <div class="form-check">
                                    <label class="form-check-label"><?php langSwitch('Graf:', 'Plot:');?><input class="form-check-input" type="checkbox" name="plot" id="plot"></label>
                                </div>
                            </div>

                            <button type="button" id="sendBtn" name="sendBtn" class="btn btn-dark"><?php langSwitch('Spusti:', 'Start:');?></button><br><br>
                        </form>
                    </div>
                </div> 
            </div>
        </div> 

    <!-- ---------------------------------------------------------------------------------------------------------------- -->
    <!-- Divs preparvases -->

        <div class="canvases" style="margin-bottom: 1rem;">
            <section>
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
                <h5><?php langSwitch('Príkazový riadok pre Octave:', 'Test command line for Octave:');?></h5>

                <!-- Form sends command to a function to be executed, then prints output -->
                <form action="index.php" method="post" class="form-group">
                <textarea id="output" name="out_oc" class="form-control" style="height:110px;" readonly><?php if(isset($_POST['octave_command_btn'])){ echo $commandLineOutput; }?></textarea>
                <div class="input-group">

                    <textarea id="command" name="oc_command" class="form-control" style="height:18px;"></textarea>

                    <div class="input-group-append">

                    <button class="input-group-text btn btn-dark" name="octave_command_btn"><?php langSwitch('Pošli:', 'Send:');?></button>

                    </div>
                </div>
                </form>
                <br>
            </div>
        </section>

        <br><br>
    
    </section>


    <!-- ---------------------------------------------------------------------------------------------------------------- -->


    <!-- Bootstrap script-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    
    <!-- JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

    <!-- Plotly -->
    <script src="https://cdn.plot.ly/plotly-2.12.1.min.js"></script>
   
    <script src="scripts/plot.js" defer></script>

    <!-- p5.js library for animation -->
    <script src="scripts/p5.js"></script>

    <!-- My script, for animation -->
    <script src="scripts/sketch.js"></script>
</body>
</html>
