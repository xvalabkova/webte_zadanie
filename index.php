<!DOCTYPE html>
<head>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" 
        rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
   <meta charset="UTF-8"> 
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

    <title>Zadanie</title>
 
</head> 

<!-- ---------------------------------------------------------------------------------------------------------------- -->


<body>  
  <section>
    <!-- container for adress input -->
  <div class="container border">
      
      <div class="row justify-content-center">
          <h3 class="text-center">Welcome Page</h3>
      <div class="col justify-content-end text-end">
          
          <form action="index.php" method="post" >
              <label >Language</label> 
          <small class="text-muted">(Jazyk)</small>
             <select id="lang" name="lang">
                 <option value="sk">SK</option>
                 <option value="en" selected>EN</option>
            </select>
            <button type="submit" class="btn" name="go_btn" >To page<small class="text-muted">(Na stránku)</small></button>
          </form>
        </div>
        <p>Page content.</p>
      </div>
  </div> 
</section>
    </body>
</html>
