<!-- performs query to get existing data and presents form to update  data -->
<?php
// set access level
$page_type = "admin";
include("protected.php");
// if trip_to_edit is not set then redirect to admin.php because user has not specified that they want to edit a trip
if (!isset($_SESSION["trip_to_edit"])) {
  header('Location: http://holden.ischool.utexas.edu/~group2_trips/admin.php');
  exit;
}
// otherwise extract the result of the query for a trip's information
else {
  // save the old station id just in case the user changes the trip id
  $old_trip_id = $_SESSION["trip_to_edit"][0];
  $start_time = $_SESSION["trip_to_edit"][1];
  $stop_time = $_SESSION["trip_to_edit"][2];
  $bike_id = $_SESSION["trip_to_edit"][3];
  $from_station_id = $_SESSION["trip_to_edit"][4];
  $to_station_id = $_SESSION["trip_to_edit"][5];
  $rider_id = $_SESSION["trip_to_edit"][6];
  unset($_SESSION["trip_to_edit"]);
}
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
    <title>OpenBikeShareData: Update a Trip</title>
  </head>
  <body>
    <?php include("admin_header.php"); ?>
    <div class="container">
      <br><br><br>
      <h3>Update the trip's information:</h3>
      <!-- form to update a trip's information, pre-populated with trip's current information (from station id, to station id, start time, stop time, rider id, etc.) -->
      <form class="form form-validate" role="form" action="perform_trip_update.php">
        <table class="table">
          <tr><th>Trip ID</th><th>Start Time (YYYY-MM-DD HH:MM:SS)</th><th>Stop Time (YYYY-MM-DD HH:MM:SS)</th><th>Bike ID</th><th>From Station ID</th><th>To Station ID</th><th>Rider ID</th></tr>
          <tr>
            <td><label for="new_trip_id"></label>
              <input required type="number" name="new_trip_id" value="<?= $old_trip_id ?>"></input>
            </td>       
            <td>
              <label for="start_time"></label>
              <input required type="text" name="start_time" value="<?= $start_time ?>"></input>
            </td>
            <td>
              <label for="stop_time"></label>
              <input required type="text" name="stop_time" value="<?= $stop_time ?>"></input>
            </td>
            <td>
              <label for="bike_id"></label>
              <input required type="number" name="bike_id" value="<?= $bike_id ?>"></input>
            </td>
            <td>
              <label for="from_station_id"></label>
              <input required type="number" name="from_station_id" value="<?= $from_station_id ?>"></input>
            </td>
            <td>
              <label for="to_station_id"></label>
              <input required type="number" name="to_station_id" value="<?= $to_station_id ?>"></input>
            </td>
            <td>
              <label for="rider_id"></label>
              <input required type="number" name="rider_id" value="<?= $rider_id ?>"></input>
            </td>
          </tr>
        </table>
        <!-- keep track of rider's old id so be used in the update query on the page -->
        <input type="hidden" name="old_trip_id" value="<?= $old_trip_id ?>"/>
        <div class="form-group">
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </form>
  </body>
  </html>
