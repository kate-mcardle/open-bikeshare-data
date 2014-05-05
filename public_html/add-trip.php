<!-- displays a form that the user fills out with new trip data 
  and then submits so that the trip can be added -->
<?php
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
    <title>OpenBikeShareData: Add a Trip</title>
  </head>
  <body>
    <?php include("admin_header.php"); ?>
    <div class="container">
      <br><br><br>
      <h3>Add new trip information:</h3>
      <!-- form to add new trip -->
      <form class="form form-validate" role="form" action="perform_trip_add.php">
        <table class="table">
          <tr><th>Trip ID</th><th>Start Time (YYYY-MM-DD HH:MM:SS)</th><th>Stop Time (YYYY-MM-DD HH:MM:SS)</th><th>Bike ID</th><th>Starting Station ID</th><th>Stopping Station ID</th><th>Rider ID</th></tr>
          <tr>
            <td>
              <label for="trip_id"></label>
              <input required type="number" name="trip_id"></input>
            </td>
            <td>
              <label for="start_time"></label>
              <input required type="text" name="start_time"></input>
            </td>
            <td>
              <label for="stop_time"></label>
              <input required type="text" name="stop_time"></input>
            </td>
            <td>
              <label for="bike_id"></label>
              <input required type="number" name="bike_id"></input>
            </td>
            <td>
              <label for="from_station_id"></label>
              <input required type="number" name="from_station_id"></input>
            </td>
            <td>
              <label for="to_station_id"></label>
              <input required type="number" name="to_station_id"></input>
            </td>
            <td>
              <label for="rider_id"></label>
              <input required type="number" name="rider_id"></input>
            </td>
          </tr>
        </table>
        <div class="form-group">
          <button type="submit" class="btn btn-primary">Add Trip</button>
        </div>
      </form>
    </div>
  </body>
  </html>
