<!-- displays text form for super admin to enter MySQL queries -->
<?php
// set access level
$page_type = "superuser";
include("protected.php");
?>
  <html>
    <head>
      <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
      <link rel="stylesheet" type="text/css" href="slider/css/slider.css" />
      <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
      <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
      <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
      <script type="text/javascript" src="slider/js/bootstrap-slider.js"></script>
      <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
      <title>OpenBikeshareData: Super User Data Management</title>
    </head>
    <body>
      <?php include("superuser_header.php"); ?>
      <div class="container">
        <br><br><br>
        <h3>Run SQL queries on bikeshare data:</h3>
        <!-- form with text box for super user MySQL queries -->
        <form class="form form-validate" role="form" action="/~group2_trips/result-su.php">
          <div class="form-group">
            <label for="query" class="control-label">Enter query:</label><br>
            <textarea rows="6" cols="50" value="" name="query" required/></textarea>
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary">Submit Query</button>
          </div>
        </form>
      </div>
    </body>
  </html>
