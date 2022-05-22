<!DOCTYPE html>
<head>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" 
        rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
   <meta charset="UTF-8"> 
    <title>Assignement</title>
</head> 

<!-- ---------------------------------------------------------------------------------------------------------------- -->

<?php
  if(isset($_POST['octave_command_btn'])){
    $command = strip_tags($_POST['oc_command']);
    $command = str_replace(array("\n", "\r"), '', $command);
    $commands = explode(";",$command);
  

    $myfile = fopen("../form_commands.m", "w") or die("Unable to open file!");
    $txt= "";

    for($i=0; $i<sizeof($commands);$i++){
      if($commands[$i]!="")
        $txt = $txt."disp(\"$commands[$i]=\"),disp(".$commands[$i] .");";
    }
    fwrite($myfile, $txt);
    fclose($myfile);

    $cmd = "octave -qf ../form_commands.m ";
    
  }
?>

<body>

  <br>  
  <section>
    <!-- container for parameters -->
  <div class="container border">
    <br>
      <div class="row">
          <h5>Fill in parameters:</h5>
          <form action="index.php" method="post" class="form-group">

          <div class="form-group row">
              <label for="weight1" class="col-sm-2 col-form-label">Weight 1:</label>
              <div class="col-sm-5">
                  <input type="text" class="form-control" id="weight1" name="m1" placeholder="kg">
              </div>
          </div>

          <div class="form-group row">
              <label for="weight2" class="col-sm-2 col-form-label">Weight 2:</label>
              <div class="col-sm-5">
                  <input type="text" class="form-control" id="weight2" name="m2" placeholder="kg">
              </div>
          </div>
            
              <br>

          <div class="form-group row">
              <label for="height" class="col-sm-2 col-form-label">Obstacle height:</label>
              <div class="col-sm-5">
                  <input type="text" class="form-control" id="height" name="r" placeholder="cm">
              </div>
          </div>

          <br>

          <div class="form-check">
              <input class="form-check-input" type="checkbox" checked name="animation" id="animation">
              <label class="form-check-label" for="animation">
             Animation
              </label>
          </div>

          <div class="form-check">
              <input class="form-check-input" type="checkbox" value="" id="graph">
              <label class="form-check-label" for="graph">
             Graph
              </label>
          </div>

              <br>
  	        <button id="params" name="params" class="btn btn-dark">Play</button><br><br>
          </form>
      </div>
  </div> 
</section>
<br>
<section>
    <!-- animation -->
    <div class="container border">

    </div>

</section>

<section>
    <!-- command line  -->
  <div class="container border">
    <br>
    <h5>Test command line for octave</h5>

    <form action="index.php" method="post" class="form-group">
    <textarea id="output" name="out_oc" class="form-control" style="height:110px;" readonly><?php if(isset($_POST['octave_command_btn'])){$ex=passthru($cmd, $output);}?></textarea>
      <div class="input-group">

        <textarea id="command" name="oc_command" class="form-control" style="height:18px;"></textarea>

        <div class="input-group-append">

          <button class="input-group-text btn btn-dark" name="octave_command_btn">Send</button>

        </div>
      </div>
    </form>
    <br>
  </div>
</section>
<br><br>

    </body>
</html>
