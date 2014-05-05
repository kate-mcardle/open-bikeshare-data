<!-- displays a form that the user fills out with new rider data 
  and then submits so that the rider can be added -->
<?php
// set access level
$page_type = "admin";
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
    <title>OpenBikeShareData: Add a Rider</title>
  </head>
  <body>
    <?php include("admin_header.php"); ?>
    <div class="container">
      <br><br><br>
      <h3>Add new rider information:</h3>
      <!-- form to add a rider -->
      <form class="form form-validate" role="form" action="perform_rider_add.php">
        <table class="table">
          <tr><th>Rider ID</th><th>Rider Type</th><th>Gender</th><th>Birth Year</th><tr>
          <tr>
            <td>
              <label for="rider_id"></label>
              <input required type="number" name="rider_id"></input>
            </td>
            <td>
              <label for="rider_type"></label>
              <select class="form-control" required name="rider_type">
                <option value="Subscriber">Subscriber</option>
                <option value="Non-Subscriber">Non-Subscriber</option>
              </select>
            </td>
            <td>
              <label for="gender"></label>
              <select class="form-control" required name="gender">
                <option value="Female">Female</option>
                <option value="Male">Male</option>
                <option value="">NA</option>
              </select>
            </td>
            <td>
              <label for="birth_year"></label>
              <input required type="text" name="birth_year"></input>
            </td>
          </tr>
        </table>
          <div class="form-group">
            <button type="submit" class="btn btn-primary">Add Rider</button>
          </div>
      </form>
    </div>
  </body>
  </html>
