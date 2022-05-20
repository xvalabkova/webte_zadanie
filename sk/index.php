<!DOCTYPE html>
<head>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" 
        rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
   <meta charset="UTF-8"> 
    <title>Zadanie</title>
</head> 

<!-- ---------------------------------------------------------------------------------------------------------------- -->


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
              <label for="spring_c1" class="col-sm-2 col-form-label">Spring Constant 1:</label>
              <div class="col-sm-5">
                  <input type="text" class="form-control" id="spring_c1" name="k1" placeholder="N/m">
              </div>
          </div>

          <div class="form-group row">
              <label for="spring_c2" class="col-sm-2 col-form-label">Spring Constant 2:</label>
              <div class="col-sm-5">
                  <input type="text" class="form-control" id="spring_c2" name="k2" placeholder="N/m">
              </div>
          </div>

              <br>

          <div class="form-group row">
              <label for="ampl1" class="col-sm-2 col-form-label">Amplitude 1</label>
              <div class="col-sm-5">
  	              <input id="ampl1" name="b1"  class="form-control" type="text" value="" placeholder="">
              </div>
          </div>

          <div class="form-group row">
              <label for="ampl2" class="col-sm-2 col-form-label">Amplitude 2</label>
              <div class="col-sm-5">
  	              <input id="ampl2" name="b2"  class="form-control" type="text" value="" placeholder="">
              </div>
          </div>

              <br>
  	        <button id="params" name="params" class="btn btn-dark">Play animation</button><br><br>
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
      <div class="input-group">

        <textarea id="command" name="command" aria-label="With textarea" class="form-control" style="height:18px;"></textarea>

        <div class="input-group-append">

          <button class="input-group-text btn btn-dark">Send</button>

        </div>
      </div>
    </form>
    <br>
  </div>
</section>
<br><br>

    </body>
</html>
