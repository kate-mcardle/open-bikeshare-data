<!-- displays a form that the user fills out with new station data 
  and then submits so that the station can be added -->

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
    <title>OpenBikeShareData: Add a Station</title>
  </head>
  <body>
    <?php include("admin_header.php"); ?>
    <div class="container">
      <br><br><br>
      <h3>Add new station information:</h3>
      <!-- form to add station -->
      <form class="form form-validate" role="form" action="perform_station_add.php">
        <table class="table">
          <tr><th>Station ID</th><th>Station Name</th><th>Latitude</th><th>Longitude</th><th>Bike Capacity</th></tr>
          <tr>
            <td>
              <label for="station_id"></label>
              <input required type="number" name="station_id"></input>
            </td>
            <td>
              <label for="station_name"></label>
              <input required type="text" name="station_name"></input>
            </td>
            <td>
              <label for="lat"></label>
              <input required type="number" step="any" name="lat"></input>
            </td>
            <td>
              <label for="lng"></label>
              <input required type="number" step="any" name="lng"></input>
            </td>
            <td>
              <label for="bike_capacity"></label>
              <input required type="number" name="bike_capacity"></input>
            </td>
          </tr>
        </table>
        <div class="form-group">
          <button type="submit" class="btn btn-primary">Add Station</button>
        </div>
      </form>
    </div>
  </body>
  </html>
